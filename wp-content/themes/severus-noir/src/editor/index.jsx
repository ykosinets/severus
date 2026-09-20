/**
 * The editor side of the section blocks.
 *
 * One schema, mirroring inc/block-sections.php, describes what each block
 * holds; the controls are generated from it. A section's headline is edited
 * in place, everything else sits in the sidebar, and rows are child blocks so
 * they can be dragged.
 *
 * Adding a field to a section means an entry here and an entry in the PHP
 * table — nothing else.
 */
const { registerBlockType } = wp.blocks;
const {
  InnerBlocks,
  useBlockProps,
  useInnerBlocksProps,
  RichText,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} = wp.blockEditor;
const { PanelBody, TextControl, TextareaControl, Button, FormTokenField, Disabled, Spinner } = wp.components;
const { useSelect } = wp.data;
const { useState, useEffect, RawHTML } = wp.element;
const { serialize } = wp.blocks;
const apiFetch = wp.apiFetch;
const { __ } = wp.i18n;

/* rich: edited in place. text/area: sidebar. media: attachment picker.
   link: url + label. posts: pick published posts of a type. */
const SECTIONS = {
  turn: { fields: { title: ['rich', 'Line'] } },
  hero: {
    fields: {
      title: ['rich', 'Headline'],
      intro: ['area', 'Lede'],
      videoId: ['media', 'Video', 'video'],
      posterId: ['media', 'Poster'],
      duration: ['text', 'Video length'],
      primary: ['link', 'Primary button'],
      secondary: ['link', 'Secondary button'],
    },
    rows: ['severus/hero-stat'],
  },
  familiar: { fields: { title: ['rich', 'Heading'], intro: ['area', 'Intro'] }, rows: ['severus/familiar-card'] },
  services: { fields: { title: ['rich', 'Heading'], text: ['area', 'Intro'], button: ['link', 'Button'] } },
  method: {
    fields: { title: ['rich', 'Heading'], subtitle: ['area', 'Subtitle'], description: ['area', 'Description'] },
    rows: ['severus/method-step'],
  },
  impact: { fields: { title: ['rich', 'Heading'], subtitle: ['area', 'Subtitle'] }, rows: ['severus/impact-slide'] },
  results: { fields: { title: ['rich', 'Heading'], text: ['area', 'Intro'], cases: ['posts', 'Cases', 'case'] } },
  trust: {
    fields: { title: ['rich', 'Heading'], text: ['area', 'Description'], logoId: ['media', 'Mark'], button: ['link', 'Button'] },
    rows: ['severus/trust-figure', 'severus/trust-note'],
  },
  voices: {
    fields: {
      title: ['rich', 'Heading'],
      kicker: ['text', 'Kicker'],
      text: ['area', 'Intro'],
      button: ['link', 'Button'],
      reviews: ['posts', 'Reviews', 'review'],
    },
  },
  journal: { fields: { title: ['rich', 'Heading'], button: ['link', 'Button'], posts: ['posts', 'Articles', 'post'] } },
  people: { fields: { title: ['rich', 'Heading'], intro: ['area', 'Intro'] }, rows: ['severus/person'] },
  faq: { fields: { title: ['rich', 'Heading'] }, rows: ['severus/faq-item'] },
  talk: { fields: { title: ['rich', 'Heading'], text: ['area', 'Intro'], logoId: ['media', 'Mark'] } },
};

const ROWS = {
  'severus/hero-stat': { iconId: ['media', 'Icon'], link: ['link', 'Link'], text: ['rich', 'Text'] },
  'severus/familiar-card': { imageId: ['media', 'Image'], heading: ['rich', 'Heading'], text: ['rich', 'Text'] },
  'severus/method-step': { text: ['rich', 'Step'] },
  'severus/impact-slide': {
    title: ['rich', 'Title'],
    text: ['rich', 'Text'],
    imageId: ['media', 'Image'],
    button: ['link', 'Button'],
  },
  'severus/person': {
    photoId: ['media', 'Photo'],
    name: ['rich', 'Name'],
    role: ['text', 'Role'],
    quote: ['area', 'Quote'],
    overlay: ['text', 'Role on the photo'],
    linkedin: ['text', 'LinkedIn URL'],
  },
  'severus/trust-figure': { number: ['text', 'Figure'], text: ['rich', 'Caption'] },
  'severus/trust-note': { text: ['rich', 'Note'] },
  'severus/faq-item': { question: ['rich', 'Question'], answer: ['area', 'Answer'] },
};

/** An attachment picker that shows what is chosen. */
function Media({ label, value, onChange, kind }) {
  const media = useSelect((select) => (value ? select('core').getMedia(value) : null), [value]);

  return (
    <MediaUploadCheck>
      <MediaUpload
        allowedTypes={[kind || 'image']}
        value={value}
        onSelect={(item) => onChange(item.id)}
        render={({ open }) => (
          <div className="severus-field">
            <span className="severus-field__label">{label}</span>
            <Button variant="secondary" onClick={open}>
              {media ? media.title?.rendered || media.slug : __('Choose…', 'severus-noir')}
            </Button>
            {value ? (
              <Button variant="link" isDestructive onClick={() => onChange(0)}>
                {__('Clear', 'severus-noir')}
              </Button>
            ) : null}
          </div>
        )}
      />
    </MediaUploadCheck>
  );
}

/** A link: where it goes and what it says. */
function Link({ label, value, onChange }) {
  const link = value || {};

  return (
    <div className="severus-field">
      <TextControl
        label={`${label} — ${__('label', 'severus-noir')}`}
        value={link.title || ''}
        onChange={(title) => onChange({ ...link, title })}
      />
      <TextControl
        label={`${label} — ${__('URL', 'severus-noir')}`}
        value={link.url || ''}
        onChange={(url) => onChange({ ...link, url })}
      />
    </div>
  );
}

/** Published posts of one type, picked by title. */
function Posts({ label, value, onChange, postType }) {
  const options = useSelect(
    (select) => select('core').getEntityRecords('postType', postType, { per_page: 100, status: 'publish' }) || [],
    [postType]
  );

  const titleOf = (id) => options.find((post) => post.id === id)?.title?.rendered || `#${id}`;
  const idOf = (title) => options.find((post) => post.title?.rendered === title)?.id;

  return (
    <FormTokenField
      label={label}
      value={(value || []).map(titleOf)}
      suggestions={options.map((post) => post.title?.rendered)}
      onChange={(titles) => onChange(titles.map(idOf).filter(Boolean))}
      __experimentalExpandOnFocus
    />
  );
}

/** The sidebar controls for everything that is not edited in place. */
function Fields({ fields, attributes, setAttributes, sidebarAll }) {
  const controls = Object.entries(fields).filter(([, spec]) => sidebarAll || spec[0] !== 'rich');

  if (!controls.length) {
    return null;
  }

  return (
    <InspectorControls>
      <PanelBody title={__('Content', 'severus-noir')}>
        {controls.map(([name, [kind, label, extra]]) => {
          const set = (value) => setAttributes({ [name]: value });

          if (kind === 'media') {
            return <Media key={name} label={label} value={attributes[name]} onChange={set} kind={extra} />;
          }
          if (kind === 'link') {
            return <Link key={name} label={label} value={attributes[name]} onChange={set} />;
          }
          if (kind === 'posts') {
            return <Posts key={name} label={label} value={attributes[name]} onChange={set} postType={extra} />;
          }
          if (kind === 'area' || kind === 'rich') {
            return (
              <TextareaControl key={name} label={label} value={attributes[name] || ''} onChange={set} />
            );
          }
          return <TextControl key={name} label={label} value={attributes[name] || ''} onChange={set} />;
        })}
      </PanelBody>
    </InspectorControls>
  );
}

/**
 * What the section will actually look like: its own markup, rendered by the
 * same PHP the page uses, asked for again a moment after an edit settles.
 */
function Preview({ clientId }) {
  const { markup, postId } = useSelect(
    (select) => {
      const block = select('core/block-editor').getBlock(clientId);
      return {
        markup: block ? serialize([block]) : '',
        postId: select('core/editor')?.getCurrentPostId?.() || 0,
      };
    },
    [clientId]
  );

  const [html, setHtml] = useState('');
  const [busy, setBusy] = useState(true);

  useEffect(() => {
    let live = true;
    setBusy(true);

    const timer = setTimeout(() => {
      apiFetch({
        path: '/severus/v1/preview',
        method: 'POST',
        data: { content: markup, post_id: postId },
      })
        .then((response) => live && setHtml(response.html || ''))
        .catch(() => live && setHtml(''))
        .finally(() => live && setBusy(false));
    }, 400);

    return () => {
      live = false;
      clearTimeout(timer);
    };
  }, [markup, postId]);

  if (!html) {
    return (
      <div className="severus-preview severus-preview--empty">
        {busy ? <Spinner /> : __('Nothing to show yet.', 'severus-noir')}
      </div>
    );
  }

  return (
    <Disabled>
      <div className="severus-preview">
        <RawHTML>{html}</RawHTML>
        {busy ? <span className="severus-preview__busy"><Spinner /></span> : null}
      </div>
    </Disabled>
  );
}

/** The in-place fields, largest first, so a section reads like what it is. */
function Rich({ fields, attributes, setAttributes, tag }) {
  return Object.entries(fields)
    .filter(([, spec]) => spec[0] === 'rich')
    .map(([name, [, label]], index) => (
      <RichText
        key={name}
        tagName={index === 0 ? tag : 'p'}
        className={`severus-section__${index === 0 ? 'title' : 'line'}`}
        placeholder={label}
        value={attributes[name] || ''}
        onChange={(value) => setAttributes({ [name]: value })}
        allowedFormats={['core/bold', 'core/italic']}
      />
    ));
}

Object.entries(SECTIONS).forEach(([name, { fields, rows }]) => {
  registerBlockType(`severus/${name}`, {
    edit({ attributes, setAttributes, clientId }) {
      const blockProps = useBlockProps({ className: 'severus-block' });
      const inner = rows
        ? useInnerBlocksProps(
            { className: 'severus-block__rows' },
            { allowedBlocks: rows, template: [[rows[0]]], templateLock: false }
          )
        : null;

      return (
        <>
          <Fields fields={{ ...fields }} attributes={attributes} setAttributes={setAttributes} sidebarAll />
          <div {...blockProps}>
            <Preview clientId={clientId} />
            {inner ? (
              <div className="severus-block__edit">
                <p className="severus-block__legend">{__('Rows', 'severus-noir')}</p>
                <div {...inner} />
              </div>
            ) : null}
          </div>
        </>
      );
    },
    /* A dynamic block saves nothing of its own — the render callback draws
       the section — but a section that holds rows must still write them out.
       Gutenberg serialises a block whose save() is empty as self-closing,
       and a self-closing block has nowhere to put its children: saving one
       in the editor would drop every row it holds. InnerBlocks.Content is
       what keeps them in the markup for parse_blocks() to read back. */
    save: () => (rows ? <InnerBlocks.Content /> : null),
  });
});

Object.entries(ROWS).forEach(([name, fields]) => {
  registerBlockType(name, {
    edit({ attributes, setAttributes }) {
      return (
        <div {...useBlockProps({ className: 'severus-row' })}>
          <Fields fields={fields} attributes={attributes} setAttributes={setAttributes} sidebarAll />
          <Rich fields={fields} attributes={attributes} setAttributes={setAttributes} tag="h3" />
        </div>
      );
    },
    save: () => null,
  });
});

/* The bridge block: a section still edited in this page's fields. */
registerBlockType('severus/section', {
  edit({ attributes }) {
    return (
      <div {...useBlockProps({ className: 'severus-placeholder' })}>
        <strong>{attributes.name || __('Section', 'severus-noir')}</strong>
        <span>{__('Edited in this page’s fields, below the editor.', 'severus-noir')}</span>
      </div>
    );
  },
  save: () => null,
});
