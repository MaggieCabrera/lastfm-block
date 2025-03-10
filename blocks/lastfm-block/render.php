<?php
/**
 * LastFM Block Render
 *
 * @package LastFM_Block
 */

/**
 * Render callback for the LastFM block.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content.
 * @param WP_Block $block      Block instance.
 * @return string
 */
function render_lastfm_block( $attributes, $content, $block ) {
    if ( empty( $attributes['lastfmUser'] ) ) {
        return '<p>' . __( 'Please enter a LastFM username.', 'lastfm-block' ) . '</p>';
    }

    $lastfm_user = $attributes['lastfmUser'];
    $lastfm_tracks = 5; // Default to 5 tracks for the block version

    $lastfm_api = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=' . $lastfm_user . '&api_key=b3f34d8652bf87d8d1dcbfa5c53d245d&limit=' . $lastfm_tracks;
    $lastfm_response = @simplexml_load_file($lastfm_api);

    $lastfm_api_user = 'http://ws.audioscrobbler.com/2.0/?method=user.getinfo&user=' . $lastfm_user . '&api_key=b3f34d8652bf87d8d1dcbfa5c53d245d';
    $lastfm_user_info = @simplexml_load_file($lastfm_api_user);

    if (!$lastfm_user_info || !$lastfm_response) {
        return '<p>' . __( 'Error loading LastFM data. Please check the username and try again.', 'lastfm-block' ) . '</p>';
    }

    $user_name = $lastfm_user_info->user->name;
    $realname = $lastfm_user_info->user->realname;
    $user_url = $lastfm_user_info->user->url;
    $userpicture = $lastfm_user_info->user->image[2];
    $scrobbles = $lastfm_user_info->user->playcount;

    ob_start();
    ?>
    <div class="wp-block-lastfm-block-lastfm-tracks">
        <div class="lastfm-row lastfm-user">
            <div class="lastfm-col-quarter">
                <img width="100%" height="100%" src="<?php echo esc_url($userpicture); ?>" alt="<?php echo esc_attr($user_name); ?>" />
            </div>
            <div class="lastfm-col-center">
                <b><?php echo esc_html($realname); ?></b><br>
                <a target="_blank" href="<?php echo esc_url($user_url); ?>"><?php echo esc_html($user_name); ?></a><br>
                <small><?php echo esc_html($scrobbles); ?> Tracks</small>
            </div>
        </div>

        <?php foreach ($lastfm_response->recenttracks->track as $tracks) : 
            $img = ($tracks->image[1]->__toString()) ? $tracks->image[2] : "https://lastfm-img2.akamaized.net/i/u/64s/c6f59c1e5e7240a4c0d427abd71f3dbb.png";
            $name = $tracks->name;
            $artist = $tracks->artist;
            $time = isset($tracks->date) ? human_time_diff($tracks->date['uts']) : '';
            $nowplaying = $tracks['nowplaying'];
            $time_final = ($nowplaying != "") ? "now playing..." : $time;
        ?>
            <div class="lastfm-row lastfm-tracklist">
                <div class="lastfm-col-twenty">
                    <img width="100%" height="100%" src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>" />
                </div>
                <div class="lastfm-col">
                    <small><b><?php echo esc_html($name); ?></b></small><br>
                    <small><?php echo esc_html($artist); ?></small><br>
                    <small><?php echo esc_html($time_final); ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
} 