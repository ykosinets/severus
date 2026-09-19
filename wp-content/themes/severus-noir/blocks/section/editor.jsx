/**
 * A section that still reads its own fields: nothing to edit here, so the
 * block says which section it is and leaves it at that.
 */
const { registerBlockType } = wp.blocks;
const { useBlockProps } = wp.blockEditor;
const { __ } = wp.i18n;

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
