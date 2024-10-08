import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, ColorPicker, ToggleControl } from '@wordpress/components';
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
        customColor: {
            type: 'string',
            default: '#000000',
        },
        spacing: {
            type: 'string',
            default: '10px',
        },
        marginTop: {
            type: 'string',
            default: '0px',
        },
        marginRight: {
            type: 'string',
            default: '0px',
        },
        marginBottom: {
            type: 'string',
            default: '0px',
        },
        marginLeft: {
            type: 'string',
            default: '0px',
        },
        linkMargins: {
            type: 'boolean',
            default: false,
        },
    },
    
    // Block editor setup for "All Icons"
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { iconType, iconSize, iconStyle, iconAlignment, customColor, spacing, marginTop, marginRight, marginBottom, marginLeft, linkMargins } = attributes;
        const blockProps = useBlockProps();

        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Icon Settings', 'show-my-social-icons')}>
                        <SelectControl
                            label={__('Icon Type', 'show-my-social-icons')}
                            value={iconType}
                            options={[
                                { label: 'SVG', value: 'SVG' },
                                { label: 'PNG', value: 'PNG' },
                            ]}
                            onChange={(newIconType) => setAttributes({ iconType: newIconType })}
                        />
                        <SelectControl
                            label={__('Icon Style', 'show-my-social-icons')}
                            value={iconStyle}
                            options={
                                iconType === 'SVG'
                                    ? [
                                        { label: 'Icon only black', value: 'Icon only black' },
                                        { label: 'Icon only white', value: 'Icon only white' },
                                        { label: 'Icon only custom color', value: 'Icon only custom color' },
                                      ]
                                    : [
                                        { label: 'Icon only full color', value: 'Icon only full color' },
                                        { label: 'Icon only black', value: 'Icon only black' },
                                        { label: 'Icon only white', value: 'Icon only white' },
                                        { label: 'Full logo horizontal', value: 'Full logo horizontal' },
                                        { label: 'Full logo square', value: 'Full logo square' },
                                      ]
                            }
                            onChange={(newIconStyle) => setAttributes({ iconStyle: newIconStyle })}
                        />
                        {iconType === 'SVG' && iconStyle === 'Icon only custom color' && (
                            <ColorPicker
                                label={__('Custom Color', 'show-my-social-icons')}
                                color={customColor}
                                onChangeComplete={(newColor) => setAttributes({ customColor: newColor.hex })}
                            />
                        )}
                        <TextControl
                            label={__('Icon Size', 'show-my-social-icons')}
                            value={iconSize}
                            onChange={(newIconSize) => setAttributes({ iconSize: newIconSize })}
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
                        <TextControl
                            label={__('Icon Spacing', 'show-my-social-icons')}
                            value={spacing}
                            onChange={(newSpacing) => setAttributes({ spacing: newSpacing })}
                        />
                        <PanelBody title={__('Container Margins', 'show-my-social-icons')} initialOpen={false}>
                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                                <TextControl
                                    label={__('Top', 'show-my-social-icons')}
                                    value={marginTop}
                                    onChange={(newMarginTop) => setAttributes({ marginTop: newMarginTop })}
                                    style={{ width: '48%' }}
                                />
                                <TextControl
                                    label={__('Bottom', 'show-my-social-icons')}
                                    value={marginBottom}
                                    onChange={(newMarginBottom) => setAttributes({ marginBottom: newMarginBottom })}
                                    style={{ width: '48%' }}
                                />
                            </div>
                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                                <TextControl
                                    label={__('Left', 'show-my-social-icons')}
                                    value={marginLeft}
                                    onChange={(newMarginLeft) => setAttributes({ marginLeft: newMarginLeft })}
                                    style={{ width: '48%' }}
                                />
                                <TextControl
                                    label={__('Right', 'show-my-social-icons')}
                                    value={marginRight}
                                    onChange={(newMarginRight) => setAttributes({ marginRight: newMarginRight })}
                                    style={{ width: '48%' }}
                                />
                            </div>
                            <ToggleControl
                                label={__('Link Margins', 'show-my-social-icons')}
                                checked={linkMargins}
                                onChange={(newLinkMargins) => {
                                    setAttributes({ linkMargins: newLinkMargins });
                                    if (newLinkMargins) {
                                        const newMargin = marginTop || marginRight || marginBottom || marginLeft || '0px';
                                        setAttributes({
                                            marginTop: newMargin,
                                            marginRight: newMargin,
                                            marginBottom: newMargin,
                                            marginLeft: newMargin
                                        });
                                    }
                                }}
                            />
                        </PanelBody>
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
            default: 'Facebook',
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
        marginTop: {
            type: 'string',
            default: '0px',
        },
        marginRight: {
            type: 'string',
            default: '0px',
        },
        marginBottom: {
            type: 'string',
            default: '0px',
        },
        marginLeft: {
            type: 'string',
            default: '0px',
        },
        linkMargins: {
            type: 'boolean',
            default: false,
        },
    },
    
    // Block editor setup for "Single Icon"
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { platform, iconType, iconSize, iconStyle, iconAlignment, customColor, marginTop, marginRight, marginBottom, marginLeft, linkMargins } = attributes;
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
                                { label: 'SVG', value: 'SVG' },
                                { label: 'PNG', value: 'PNG' },
                            ]}
                            onChange={(newIconType) => setAttributes({ iconType: newIconType })}
                        />
                        <SelectControl
                            label={__('Icon Style', 'show-my-social-icons')}
                            value={iconStyle}
                            options={
                                iconType === 'SVG'
                                    ? [
                                        { label: 'Icon only black', value: 'Icon only black' },
                                        { label: 'Icon only white', value: 'Icon only white' },
                                        { label: 'Icon only custom color', value: 'Icon only custom color' },
                                      ]
                                    : [
                                        { label: 'Icon only full color', value: 'Icon only full color' },
                                        { label: 'Icon only black', value: 'Icon only black' },
                                        { label: 'Icon only white', value: 'Icon only white' },
                                        { label: 'Full logo horizontal', value: 'Full logo horizontal' },
                                        { label: 'Full logo square', value: 'Full logo square' },
                                      ]
                            }
                            onChange={(newIconStyle) => setAttributes({ iconStyle: newIconStyle })}
                        />
                        {iconType === 'SVG' && iconStyle === 'Icon only custom color' && (
                            <ColorPicker
                                label={__('Custom Color', 'show-my-social-icons')}
                                color={customColor}
                                onChangeComplete={(newColor) => setAttributes({ customColor: newColor.hex })}
                            />
                        )}
                        <TextControl
                            label={__('Icon Size', 'show-my-social-icons')}
                            value={iconSize}
                            onChange={(newIconSize) => setAttributes({ iconSize: newIconSize })}
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
                        <PanelBody title={__('Container Margins', 'show-my-social-icons')} initialOpen={false}>
                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                                <TextControl
                                    label={__('Top', 'show-my-social-icons')}
                                    value={marginTop}
                                    onChange={(newMarginTop) => setAttributes({ marginTop: newMarginTop })}
                                    style={{ width: '48%' }}
                                />
                                <TextControl
                                    label={__('Bottom', 'show-my-social-icons')}
                                    value={marginBottom}
                                    onChange={(newMarginBottom) => setAttributes({ marginBottom: newMarginBottom })}
                                    style={{ width: '48%' }}
                                />
                            </div>
                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                                <TextControl
                                    label={__('Left', 'show-my-social-icons')}
                                    value={marginLeft}
                                    onChange={(newMarginLeft) => setAttributes({ marginLeft: newMarginLeft })}
                                    style={{ width: '48%' }}
                                />
                                <TextControl
                                    label={__('Right', 'show-my-social-icons')}
                                    value={marginRight}
                                    onChange={(newMarginRight) => setAttributes({ marginRight: newMarginRight })}
                                    style={{ width: '48%' }}
                                />
                            </div>
                            <ToggleControl
                                label={__('Link Margins', 'show-my-social-icons')}
                                checked={linkMargins}
                                onChange={(newLinkMargins) => {
                                    setAttributes({ linkMargins: newLinkMargins });
                                    if (newLinkMargins) {
                                        const newMargin = marginTop || marginRight || marginBottom || marginLeft || '0px';
                                        setAttributes({
                                            marginTop: newMargin,
                                            marginRight: newMargin,
                                            marginBottom: newMargin,
                                            marginLeft: newMargin
                                        });
                                    }
                                }}
                            />
                        </PanelBody>
                    </PanelBody>
                </InspectorControls>
                <div className="smsi-block-preview">
                    <p>{__('Social Media Icon (Single) will appear here', 'show-my-social-icons')}</p>
                </div>
            </div>
        );
    },

    // No save function because this is a dynamic block
    save: function() {
        return null;
    }
});