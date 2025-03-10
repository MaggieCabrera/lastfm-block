import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
import React from 'react';

import './editor.scss';

interface LastFMBlockAttributes {
    lastfmUser: string;
}

interface EditProps {
    attributes: LastFMBlockAttributes;
    setAttributes: (attrs: Partial<LastFMBlockAttributes>) => void;
}

export default function Edit({ attributes, setAttributes }: EditProps): JSX.Element {
    const blockProps = useBlockProps();
    const { lastfmUser } = attributes;

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('LastFM Settings', 'lastfm-block')}>
                    <TextControl
                        label={__('LastFM Username', 'lastfm-block')}
                        value={lastfmUser}
                        onChange={(value: string) => setAttributes({ lastfmUser: value })}
                        help={__('Enter your LastFM username to display your recent tracks', 'lastfm-block')}
                    />
                </PanelBody>
            </InspectorControls>
            
            <ServerSideRender
                block="lastfm-block/lastfm-tracks"
                attributes={attributes}
            />
        </div>
    );
} 