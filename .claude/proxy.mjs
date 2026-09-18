/* Test-only proxy: the in-app browser blocks subresources on the ddev origin,
   so this republishes the site on http://localhost:4173 with the ddev Host
   header and rewrites absolute URLs in text responses. */
import http from 'node:http';
import https from 'node:https';

const HOST = 'severus.ddev.site';
const ORIGINS = [`https://${HOST}`, `http://${HOST}`];
const LOCAL = 'http://localhost:4173';

http.createServer((req, res) => {
  const upstream = https.request(
    {
      /* *.ddev.site resolves to a LAN address on this network, so go straight
         to the loopback router and carry the vhost in the Host header. */
      host: '127.0.0.1',
      servername: HOST,
      path: req.url,
      method: req.method,
      headers: { ...req.headers, host: HOST, 'accept-encoding': 'identity' },
      rejectUnauthorized: false,
    },
    proxied => {
      const type = proxied.headers['content-type'] || '';
      const rewritable = /text\/html|text\/css|application\/json|javascript/.test(type);

      const headers = { ...proxied.headers };
      delete headers['content-encoding'];
      delete headers['content-length'];
      if (headers.location) {
        for (const origin of ORIGINS) headers.location = headers.location.split(origin).join(LOCAL);
      }

      if (!rewritable) {
        res.writeHead(proxied.statusCode, headers);
        return proxied.pipe(res);
      }

      const chunks = [];
      proxied.on('data', chunk => chunks.push(chunk));
      proxied.on('end', () => {
        let body = Buffer.concat(chunks).toString('utf8');
        for (const origin of ORIGINS) body = body.split(origin).join(LOCAL);
        res.writeHead(proxied.statusCode, headers);
        res.end(body);
      });
    }
  );

  upstream.on('error', error => {
    res.writeHead(502);
    res.end(String(error));
  });

  req.pipe(upstream);
}).listen(4173, () => console.log('proxying', HOST, 'on', LOCAL));
