/**
 * One slab: an image, a heading and a line of text.
 */
const { registerBlockType } = wp.blocks;
const { useBlockProps, RichText, MediaUpload, MediaUploadCheck } = wp.blockEditor;
const { Button } = wp.components;
const { useSelect } = wp.data;
const { __ } = wp.i18n;

registerBlockType('severus/familiar-card', {
  edit({ attributes, setAttributes }) {
    const { imageId, heading, text } = attributes;
    const image = useSelect(
      (select) => (imageId ? select('core').getMedia(imageId) : null),
      [imageId]
    );

    return (
      <article {...useBlockProps({ className: 'severus-slab' })}>
        <MediaUploadCheck>
          <MediaUpload
            allowedTypes={['image']}
            value={imageId}
            onSelect={(media) => setAttributes({ imageId: media.id })}
            render={({ open }) => (
              <Button variant="secondary" onClick={open} className="severus-slab__art">
                {image ? (
                  <img src={image.source_url} alt="" />
                ) : (
                  __('Choose image', 'severus-noir')
                )}
              </Button>
            )}
          />
        </MediaUploadCheck>
        <div className="severus-slab__body">
          <RichText
            tagName="h3"
            placeholder={__('Heading', 'severus-noir')}
            value={heading}
            onChange={(value) => setAttributes({ heading: value })}
            allowedFormats={['core/bold', 'core/italic']}
          />
          <RichText
            tagName="p"
            placeholder={__('Text', 'severus-noir')}
            value={text}
            onChange={(value) => setAttributes({ text: value })}
            allowedFormats={['core/bold', 'core/italic']}
          />
        </div>
      </article>
    );
  },
  save: () => null,
});
