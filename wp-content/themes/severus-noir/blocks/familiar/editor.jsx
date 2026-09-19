/**
 * "Sound familiar": a heading and intro, then the slabs as child blocks so
 * they can be reordered by dragging.
 */
const { registerBlockType } = wp.blocks;
const { useBlockProps, useInnerBlocksProps, RichText, InspectorControls } = wp.blockEditor;
const { PanelBody, TextareaControl } = wp.components;
const { __ } = wp.i18n;

const TEMPLATE = [['severus/familiar-card'], ['severus/familiar-card']];

registerBlockType('severus/familiar', {
  edit({ attributes, setAttributes }) {
    const blockProps = useBlockProps({ className: 'severus-section' });
    const innerProps = useInnerBlocksProps(
      { className: 'severus-section__rows' },
      {
        allowedBlocks: ['severus/familiar-card'],
        template: TEMPLATE,
        /* The page's composition is locked; the slabs inside it are not. */
        templateLock: false,
      }
    );

    return (
      <>
        <InspectorControls>
          <PanelBody title={__('Intro', 'severus-noir')}>
            <TextareaControl
              label={__('Intro text', 'severus-noir')}
              value={attributes.intro}
              onChange={(intro) => setAttributes({ intro })}
            />
          </PanelBody>
        </InspectorControls>
        <section {...blockProps}>
          <RichText
            tagName="h2"
            className="severus-section__title"
            placeholder={__('Sound familiar?', 'severus-noir')}
            value={attributes.title}
            onChange={(title) => setAttributes({ title })}
            allowedFormats={['core/bold', 'core/italic']}
          />
          <div {...innerProps} />
        </section>
      </>
    );
  },
  save: () => null,
});
