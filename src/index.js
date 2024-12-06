import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, ColorPicker, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

export default function Edit( { attributes, setAttributes } ) {
    const {
        marginTop,
        marginRight,
        marginBottom,
        marginLeft,
        linkMargins,
    } = attributes;

    const supportedPlatforms = [
        { id: 'address', name: 'Address' },
        { id: 'amazon', name: 'Amazon' },
        { id: 'audible', name: 'Audible' },
        { id: 'barnesandnoble', name: 'Barnes & Noble' },
        { id: 'behance', name: 'Behance' },
        { id: 'bitchute', name: 'Bitchute' },
        { id: 'bookbub', name: 'BookBub' },
        { id: 'cashapp', name: 'CashApp' },
        { id: 'clouthub', name: 'CloutHub' },
        { id: 'digg', name: 'Digg' },
        { id: 'discord', name: 'Discord' },
        { id: 'email', name: 'Email' },
        { id: 'facebook', name: 'Facebook' },
        { id: 'fiverr', name: 'Fiverr' },
        { id: 'gab', name: 'Gab' },
        { id: 'github', name: 'GitHub' },
        { id: 'givesendgo', name: 'GiveSendGo' },
        { id: 'goodreads', name: 'Goodreads' },
        { id: 'instagram', name: 'Instagram' },
        { id: 'landlinephone', name: 'Landline Phone' },
        { id: 'librarything', name: 'LibraryThing' },
        { id: 'linkedin', name: 'LinkedIn' },
        { id: 'linktree', name: 'Linktree' },
        { id: 'locals', name: 'Locals' },
        { id: 'mastodon', name: 'Mastodon' },
        { id: 'minds', name: 'Minds' },
        { id: 'myspace', name: 'MySpace' },
        { id: 'odysee', name: 'Odysee' },
        { id: 'parler', name: 'Parler' },
        { id: 'patreon', name: 'Patreon' },
        { id: 'paypal', name: 'PayPal' },
        { id: 'phone', name: 'Phone' },
        { id: 'pinterest', name: 'Pinterest' },
        { id: 'publicsq', name: 'Public Square' },
        { id: 'quora', name: 'Quora' },
        { id: 'reddit', name: 'Reddit' },
        { id: 'rokfin', name: 'Rokfin' },
        { id: 'rumble', name: 'Rumble' },
        { id: 'snapchat', name: 'Snapchat' },
        { id: 'substack', name: 'Substack' },
        { id: 'telegram', name: 'Telegram' },
        { id: 'tiktok', name: 'TikTok' },
        { id: 'truth_social', name: 'Truth Social' },
        { id: 'twitch', name: 'Twitch' },
        { id: 'twitter_x', name: 'X (formerly Twitter)' },
        { id: 'unite', name: 'Unite' },
        { id: 'venmo', name: 'Venmo' },
        { id: 'vimeo', name: 'Vimeo' },
        { id: 'vk', name: 'VK' },
        { id: 'website', name: 'Website' },
        { id: 'whatsapp', name: 'WhatsApp' },
        { id: 'youtube', name: 'YouTube' },
        { id: 'zelle', name: 'Zelle' },
    ];

    const blockProps = useBlockProps();

    useEffect(() => {
        if (linkMargins) {
            const uniformMargin = marginTop || marginRight || marginBottom || marginLeft || '0px';
            setAttributes({
                marginTop: uniformMargin,
                marginRight: uniformMargin,
                marginBottom: uniformMargin,
                marginLeft: uniformMargin,
            });
        }
    }, [linkMargins]);

    const handleMarginChange = (side, value) => {
        if (linkMargins) {
            setAttributes({
                marginTop: value,
                marginRight: value,
                marginBottom: value,
                marginLeft: value,
            });
        } else {
            setAttributes({ [side]: value });
        }
    };

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title={ __('Margin Settings', 'show-my-social-icons') }>
                    <ToggleControl
                        label={__('Link Margins', 'show-my-social-icons')}
                        checked={linkMargins}
                        onChange={(newLinkMargins) => {
                            setAttributes({ linkMargins: newLinkMargins });
                            if (newLinkMargins) {
                                const uniformMargin = marginTop || marginRight || marginBottom || marginLeft || '0px';
                                setAttributes({
                                    marginTop: uniformMargin,
                                    marginRight: uniformMargin,
                                    marginBottom: uniformMargin,
                                    marginLeft: uniformMargin,
                                });
                            }
                        }}
                    />
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '10px' }}>
                        <TextControl
                            __nextHasNoMarginBottom={ true }
                            label={__('Top', 'show-my-social-icons')}
                            value={marginTop}
                            onChange={(value) => handleMarginChange('marginTop', value)}
                            disabled={linkMargins}
                            placeholder="0px"
                            style={{ width: '23%' }}
                        />
                        <TextControl
                            __nextHasNoMarginBottom={ true }
                            label={__('Right', 'show-my-social-icons')}
                            value={marginRight}
                            onChange={(value) => handleMarginChange('marginRight', value)}
                            disabled={linkMargins}
                            placeholder="0px"
                            style={{ width: '23%' }}
                        />
                        <TextControl
                            __nextHasNoMarginBottom={ true }
                            label={__('Bottom', 'show-my-social-icons')}
                            value={marginBottom}
                            onChange={(value) => handleMarginChange('marginBottom', value)}
                            disabled={linkMargins}
                            placeholder="0px"
                            style={{ width: '23%' }}
                        />
                        <TextControl
                            __nextHasNoMarginBottom={ true }
                            label={__('Left', 'show-my-social-icons')}
                            value={marginLeft}
                            onChange={(value) => handleMarginChange('marginLeft', value)}
                            disabled={linkMargins}
                            placeholder="0px"
                            style={{ width: '23%' }}
                        />
                    </div>
                </PanelBody>
            </InspectorControls>
            <div { ...blockProps }>
                {/* Block content preview */}
                <p>{__('Social Media Icon (Single) will appear here', 'show-my-social-icons')}</p>
            </div>
        </Fragment>
    );
}

// Example function to get current user
const getCurrentUser = () => {
    apiFetch({
        path: '/wp/v2/users/me',
        method: 'GET',
        headers: {
            'X-WP-Nonce': smsiData.nonce, // Use the localized nonce
        },
    })
    .then((user) => {
        // Handle user data as needed
        return user;
    })
    .catch((error) => {
        console.error('Error fetching user:', error);
        // Handle errors as needed
        return null;
    });
};

// Call the function as needed, for example, when the block initializes
getCurrentUser();

// Register the "All Icons" block
registerBlockType('show-my-social-icons/all-icons', {
    apiVersion: 2,
    title: __('(All Icons) Show My Social Icons', 'show-my-social-icons'),
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
                            __nextHasNoMarginBottom={ true }
                            label={__('Icon Type', 'show-my-social-icons')}
                            value={iconType}
                            options={[
                                { label: 'SVG', value: 'SVG' },
                                { label: 'PNG', value: 'PNG' },
                            ]}
                            onChange={(newIconType) => setAttributes({ iconType: newIconType })}
                        />
                        <SelectControl
                            __nextHasNoMarginBottom={ true }
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
                            __nextHasNoMarginBottom={ true }
                            label={__('Icon Size', 'show-my-social-icons')}
                            value={iconSize}
                            onChange={(newIconSize) => setAttributes({ iconSize: newIconSize })}
                        />
                        <SelectControl
                            __nextHasNoMarginBottom={ true }
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
                            __nextHasNoMarginBottom={ true }
                            label={__('Icon Spacing', 'show-my-social-icons')}
                            value={spacing}
                            onChange={(newSpacing) => setAttributes({ spacing: newSpacing })}
                        />
                        <PanelBody title={__('Container Margins', 'show-my-social-icons')} initialOpen={false}>
                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                                <TextControl
                                    __nextHasNoMarginBottom={ true }
                                    label={__('Top', 'show-my-social-icons')}
                                    value={marginTop}
                                    onChange={(newMarginTop) => setAttributes({ marginTop: newMarginTop })}
                                    style={{ width: '48%' }}
                                />
                                <TextControl
                                    __nextHasNoMarginBottom={ true }
                                    label={__('Bottom', 'show-my-social-icons')}
                                    value={marginBottom}
                                    onChange={(newMarginBottom) => setAttributes({ marginBottom: newMarginBottom })}
                                    style={{ width: '48%' }}
                                />
                            </div>
                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                                <TextControl
                                    __nextHasNoMarginBottom={ true }
                                    label={__('Left', 'show-my-social-icons')}
                                    value={marginLeft}
                                    onChange={(newMarginLeft) => setAttributes({ marginLeft: newMarginLeft })}
                                    style={{ width: '48%' }}
                                />
                                <TextControl
                                    __nextHasNoMarginBottom={ true }
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
    },
});

// Register the "Single Icon" block
registerBlockType('show-my-social-icons/single-icon', {
    apiVersion: 2,
    title: __('(Single Icon) Show My Social Icons', 'show-my-social-icons'),
    attributes: {
        platform: {
            type: 'string',
            default: '',
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
        const supportedPlatforms = smsiData.supportedPlatforms;

        const fetchPlatforms = (retry = false) => {
            apiFetch({ path: '/smsi/v1/platforms' })
                .then((fetchedPlatforms) => {
                    if (typeof fetchedPlatforms === 'object' && fetchedPlatforms !== null) {
                        const platformArray = Object.entries(fetchedPlatforms).map(([id, data]) => ({
                            id,
                            name: data.name || id,
                        }));
                        setPlatforms(platformArray);
                    } else {
                        console.error('Unexpected data format:', fetchedPlatforms);
                        setPlatforms(supportedPlatforms); // Fallback on unexpected format
                    }
                })
                .catch((error) => {
                    console.error('Error fetching platforms:', error.message);
                    if (!retry) {
                        console.log('Retrying...');
                        setTimeout(() => fetchPlatforms(true), 3000); // Retry after 3 seconds
                    } else {
                        setPlatforms(supportedPlatforms); // Fallback on persistent error
                    }
                });
        };
        
        useEffect(() => {
            fetchPlatforms();
        }, []);

        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title={__('Single Icon Settings', 'show-my-social-icons')}>
                        <SelectControl
                            __nextHasNoMarginBottom={true}
                            label={__('Platform', 'show-my-social-icons')}
                            value={platform}
                            options={
                                platforms.length > 0
                                    ? platforms.map((platform) => ({
                                        label: platform.name,
                                        value: platform.id,
                                    }))
                                    : [{ label: __('No platforms available', 'show-my-social-icons'), value: '' }]
                            }
                            onChange={(newPlatform) => setAttributes({ platform: newPlatform })}
                        />
                        <SelectControl
                            __nextHasNoMarginBottom={ true }
                            label={__('Icon Type', 'show-my-social-icons')}
                            value={iconType}
                            options={[
                                { label: 'SVG', value: 'SVG' },
                                { label: 'PNG', value: 'PNG' },
                            ]}
                            onChange={(newIconType) => setAttributes({ iconType: newIconType })}
                        />
                        <SelectControl
                            __nextHasNoMarginBottom={ true }
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
                            __nextHasNoMarginBottom={ true }
                            label={__('Icon Size', 'show-my-social-icons')}
                            value={iconSize}
                            onChange={(newIconSize) => setAttributes({ iconSize: newIconSize })}
                        />
                        <SelectControl
                            __nextHasNoMarginBottom={ true }
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
                                    __nextHasNoMarginBottom={ true }
                                    label={__('Top', 'show-my-social-icons')}
                                    value={marginTop}
                                    onChange={(newMarginTop) => setAttributes({ marginTop: newMarginTop })}
                                    style={{ width: '48%' }}
                                />
                                <TextControl
                                    __nextHasNoMarginBottom={ true }
                                    label={__('Bottom', 'show-my-social-icons')}
                                    value={marginBottom}
                                    onChange={(newMarginBottom) => setAttributes({ marginBottom: newMarginBottom })}
                                    style={{ width: '48%' }}
                                />
                            </div>
                            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px' }}>
                                <TextControl
                                    __nextHasNoMarginBottom={ true }
                                    label={__('Left', 'show-my-social-icons')}
                                    value={marginLeft}
                                    onChange={(newMarginLeft) => setAttributes({ marginLeft: newMarginLeft })}
                                    style={{ width: '48%' }}
                                />
                                <TextControl
                                    __nextHasNoMarginBottom={ true }
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