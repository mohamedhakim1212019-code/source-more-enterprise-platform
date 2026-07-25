(function (wp, data) {
    'use strict';

    if (!wp || !data || !data.groups) return;

    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var registerPlugin = wp.plugins.registerPlugin;
    var editorPackage = wp.editPost || wp.editor || {};
    var PluginDocumentSettingPanel = editorPackage.PluginDocumentSettingPanel;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var TextareaControl = wp.components.TextareaControl;
    var Notice = wp.components.Notice;
    var useSelect = wp.data.useSelect;
    var useDispatch = wp.data.useDispatch;

    if (!PluginDocumentSettingPanel) return;

    function HomepageField(props) {
        var field = props.field;
        var meta = props.meta || {};
        var editPost = props.editPost;
        var storedValue = typeof meta[field.key] === 'string' ? meta[field.key] : '';
        var displayedValue = storedValue !== '' ? storedValue : field.defaultValue;
        var Control = (field.type === 'textarea' || field.type === 'html') ? TextareaControl : TextControl;

        function changeValue(nextValue) {
            var nextMeta = Object.assign({}, meta);
            nextMeta[field.key] = nextValue;
            editPost({ meta: nextMeta });
        }

        return el(
            'div',
            {
                className: 'smt-homepage-editor__field',
                dir: data.direction
            },
            el(Control, {
                label: field.label,
                value: displayedValue,
                type: field.type === 'url' ? 'url' : 'text',
                help: field.help || undefined,
                onChange: changeValue,
                __nextHasNoMarginBottom: true
            })
        );
    }

    function HomepageEditorPanel() {
        var meta = useSelect(function (select) {
            return select('core/editor').getEditedPostAttribute('meta') || {};
        }, []);
        var editPost = useDispatch('core/editor').editPost;

        return el(
            PluginDocumentSettingPanel,
            {
                name: 'smt-homepage-showcase-content',
                title: data.title,
                className: 'smt-homepage-editor'
            },
            el(
                'div',
                {
                    className: 'smt-homepage-editor__content',
                    dir: data.direction
                },
                el(Notice, {
                    status: 'info',
                    isDismissible: false,
                    className: 'smt-homepage-editor__notice'
                }, data.intro),
                data.groups.map(function (group, groupIndex) {
                    return el(
                        PanelBody,
                        {
                            title: group.label,
                            initialOpen: groupIndex === 0,
                            key: group.key,
                            className: 'smt-homepage-editor__group'
                        },
                        group.fields.map(function (field) {
                            return el(HomepageField, {
                                field: field,
                                meta: meta,
                                editPost: editPost,
                                key: field.key
                            });
                        })
                    );
                })
            )
        );
    }

    registerPlugin('smt-homepage-showcase-editor', {
        render: HomepageEditorPanel,
        icon: 'admin-home'
    });
})(window.wp, window.smtHomepageEditor);
