import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, ColorPicker } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

// Register the "All Icons" block
registerBlockType('show-my-social-icons/all-icons', {
    attributes: {
        iconType: {
            type: 'string',
            default: 'PNG',
        },
        iconSize: {
            type: 'string',
            default: '30px',
        },
        iconStyle: {
            type: 'string',
            default: 'Icon only full color',
        },
        iconAlignment: {
            type: 'string',
            default: 'Center',
        },
        customColor: {
            type: 'string',
            default: '#000000',
        },
    },
    
    // Block editor setup for "All Icons"
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { iconType, iconSize, iconStyle, iconAlignment, customColor } = attributes;
        const blockProps = useBlockProps();

        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Icon Settings', 'show-my-social-icons')}>
                        <SelectControl
                            label={__('Icon Type', 'show-my-social-icons')}
                            value={iconType}
                            options={[
                                { label: 'PNG', value: 'PNG' },
                                { label: 'SVG', value: 'SVG' },
                            ]}
                            onChange={(newIconType) => setAttributes({ iconType: newIconType })}
                        />
                        <TextControl
                            label={__('Icon Size', 'show-my-social-icons')}
                            value={iconSize}
                            onChange={(newIconSize) => setAttributes({ iconSize: newIconSize })}
                        />
                        <SelectControl
                            label={__('Icon Style', 'show-my-social-icons')}
                            value={iconStyle}
                            options={[
                                { label: 'Icon only full color', value: 'Icon only full color' },
                                { label: 'Icon only black', value: 'Icon only black' },
                                { label: 'Icon only white', value: 'Icon only white' },
                                { label: 'Icon only custom color', value: 'Icon only custom color' },
                                { label: 'Full logo horizontal', value: 'Full logo horizontal' },
                                { label: 'Full logo square', value: 'Full logo square' },
                            ]}
                            onChange={(newIconStyle) => setAttributes({ iconStyle: newIconStyle })}
                        />
                        <SelectControl
                            label={__('Icon Alignment', 'show-my-social-icons')}
                            value={iconAlignment}
                            options={[
                                { label: 'Left', value: 'Left' },
                                { label: 'Center', value: 'Center' },
                                { label: 'Right', value: 'Right' },
                            ]}
                            onChange={(newIconAlignment) => setAttributes({ iconAlignment: newIconAlignment })}
                        />
                    </PanelBody>
                </InspectorControls>
                <div className="smsi-block-preview">
                    <p>{__('Social Media Icons (All) will appear here', 'show-my-social-icons')}</p>
                </div>
            </div>
        );
    },

    // No save function because this is a dynamic block
    save: function() {
        return null;
    }
});

// Register the "Single Icon" block
registerBlockType('show-my-social-icons/single-icon', {
    attributes: {
        platform: {
            type: 'string',
            default: 'Facebook', // Default to Facebook
        },
        iconType: {
            type: 'string',
            default: 'PNG',
        },
        iconSize: {
            type: 'string',
            default: '30px',
        },
        iconStyle: {
            type: 'string',
            default: 'Icon only full color',
        },
        iconAlignment: {
            type: 'string',
            default: 'Center',
        },
        customColor: {
            type: 'string',
            default: '#000000',
        },
    },
    
    // Block editor setup for "Single Icon"
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { platform, iconType, iconSize, iconStyle, iconAlignment, customColor } = attributes;
        const blockProps = useBlockProps();

        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Single Icon Settings', 'show-my-social-icons')}>
                        <SelectControl
                            label={__('Platform', 'show-my-social-icons')}
                            value={platform}
                            options={Object.keys(smsiPlatforms.platforms).map(platform => ({
                                label: smsiPlatforms.platforms[platform].name,
                                value: platform
                            }))}
                            onChange={(newPlatform) => setAttributes({ platform: newPlatform })}
                        />
                        <SelectControl
                            label={__('Icon Type', 'show-my-social-icons')}
                            value={iconType}
                            options={[
                                { label: 'PNG', value: 'PNG' },
                                { label: 'SVG', value: 'SVG' },
                            ]}
                            onChange={(newIconType) => setAttributes({ iconType: newIconType })}
                        />
                        <TextControl
                            label={__('Icon Size', 'show-my-social-icons')}
                            value={iconSize}
                            onChange={(newIconSize) => setAttributes({ iconSize: newIconSize })}
                        />
                        <SelectControl
                            label={__('Icon Style', 'show-my-social-icons')}
                            value={iconStyle}
                            options={[
                                { label: 'Icon only full color', value: 'Icon only full color' },
                                { label: 'Icon only black', value: 'Icon only black' },
                                { label: 'Icon only white', value: 'Icon only white' },
                                { label: 'Icon only custom color', value: 'Icon only custom color' },
                                { label: 'Full logo horizontal', value: 'Full logo horizontal' },
                                { label: 'Full logo square', value: 'Full logo square' },
                            ]}
                            onChange={(newIconStyle) => setAttributes({ iconStyle: newIconStyle })}
                        />
                        <SelectControl
                            label={__('Icon Alignment', 'show-my-social-icons')}
                            value={iconAlignment}
                            options={[
                                { label: 'Left', value: 'Left' },
                                { label: 'Center', value: 'Center' },
                                { label: 'Right', value: 'Right' },
                            ]}
                            onChange={(newIconAlignment) => setAttributes({ iconAlignment: newIconAlignment })}
                        />
                    </PanelBody>
                </InspectorControls>
                <div className="smsi-block-preview">
                    <p>{__('Social Media Icon for ' + platform + ' will appear here', 'show-my-social-icons')}</p>
                </div>
            </div>
        );
    },

    // No save function because this is a dynamic block
    save: function() {
        return null;
    }
});
