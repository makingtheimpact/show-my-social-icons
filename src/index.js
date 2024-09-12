import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

// Register the "All Icons" block
registerBlockType('show-my-social-icons/all-icons', {
    title: __('Social Media Icons (All)', 'show-my-social-icons'),
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
    },
    
    // Block editor setup for "All Icons"
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { iconType, iconSize, iconStyle, iconAlignment } = attributes;
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
    title: __('Social Media Icon (Single)', 'show-my-social-icons'),
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
    },
    
    // Block editor setup for "Single Icon"
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { platform, iconType, iconSize, iconStyle, iconAlignment } = attributes;
        const blockProps = useBlockProps();
        const [platforms, setPlatforms] = useState([]);

        useEffect(() => {
            apiFetch({ path: '/smsi/v1/platforms' })
                .then((fetchedPlatforms) => {
                    console.log('Fetched platforms:', fetchedPlatforms);
                    if (typeof fetchedPlatforms === 'object' && fetchedPlatforms !== null) {
                        const platformArray = Object.entries(fetchedPlatforms).map(([id, data]) => ({
                            id,
                            name: data.name || id
                        }));
                        setPlatforms(platformArray);
                    } else {
                        console.error('Unexpected data format:', fetchedPlatforms);
                        setPlatforms([]);
                    }
                })
                .catch((error) => {
                    console.error('Error fetching platforms:', error);
                    setPlatforms([]);
                });
        }, []);

        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Single Icon Settings', 'show-my-social-icons')}>
                        <SelectControl
                            label={__('Platform', 'show-my-social-icons')}
                            value={platform}
                            options={platforms.length ? platforms.map(platform => ({
                                label: platform.name,
                                value: platform.id
                            })) : [{ label: 'Loading...', value: '' }]}
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
                    <p>{__('Social Media Icon for ' + (platforms.find(p => p.id === platform)?.name || platform) + ' will appear here', 'show-my-social-icons')}</p>
                </div>
            </div>
        );
    },

    // No save function because this is a dynamic block
    save: function() {
        return null;
    }
});