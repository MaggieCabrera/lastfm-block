import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder, TextControl, Button } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { useState } from '@wordpress/element';

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
    const [inputValue, setInputValue] = useState(lastfmUser || '');

    return (
        <div {...blockProps}>
            <Placeholder
                label={__('LastFM Recent Tracks', 'lastfm-block')}
                instructions={__('Enter your LastFM username to display your recent tracks', 'lastfm-block')}
            >
                <div className="lastfm-block__input-group">
                    <TextControl
                        value={inputValue}
                        onChange={setInputValue}
                        placeholder={__('Your LastFM username', 'lastfm-block')}
                    />
                    <Button 
                        variant="primary"
                        onClick={() => setAttributes({ lastfmUser: inputValue })}
                    >
                        {__('Display Tracks', 'lastfm-block')}
                    </Button>
                </div>
            </Placeholder>

            {lastfmUser && (
                <ServerSideRender
                    block="lastfm-block/lastfm-tracks"
                    attributes={attributes}
                />
            )}
        </div>
    );
} 