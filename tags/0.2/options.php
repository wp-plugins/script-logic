<div class="wrap" >
    <?php screen_icon(); ?>
    <form action="options.php" method="post" id="<?php echo $plugin_id; ?>_options_form" name="<?php echo $plugin_id; ?>_options_form">
        <?php settings_fields($plugin_id . '_options'); ?>
        <h2> <?php echo SLPLUGINOPTIONS_NAME; ?> &raquo; Settings</h2>
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Scripts</a></li>
                <li><a href="#tabs-2">Style Sheets</a></li>
                <li><a href="#tabs-3">Help</a></li>
            </ul>
            <div id="tabs-1">
                <?php
                $registered_scripts = unserialize(get_option('sb_registered_scripts'));
                $registered_styles = unserialize(get_option('sb_registered_styles'));
//               echo '<pre>'; print_r($registered_scripts->queue);die;
                if (!empty($registered_scripts->queue)):
                    ?>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>Script</th>
                                <th>Logic</th>
                                <th><input type="submit" name="submit" value="Save Settings" class="button-primary right"  /></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3"><input type="submit" name="submit" value="Save Settings" class="button-primary"  /></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            $sb_settings = get_option('sb_scriptlogic');
                            $i = 0;
                            foreach ($registered_scripts->queue as $handle) :
                                $i++;
                                ?>
                                <tr>
                                    <td>
                                        <label for="sb_script<?php echo $i; ?>">
                                            <b>handle:</b> <?php echo $handle; ?><br />
                                            <?php if (!empty($registered_scripts->registered[$handle]->src)): ?>src: <small><?php echo $registered_scripts->registered[$handle]->src; ?></small><?php endif; ?>
                                        </label>
                                    </td>
                                    <td>
                                        <textarea style="width:210px; height:70px;" cols="20" rows="2" id="sb_script<?php echo $i; ?>" name="sb_scriptlogic[script][<?php echo $handle ?>]"><?php echo !empty($sb_settings['script'][$handle])? $sb_settings['script'][$handle]: ""; ?></textarea>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                <?php else: ?>
                    No scripts loaded yet, reload your website's home page <a target="_blank" href=" <?php echo get_home_url(); ?> "> by clicking here</a> or hit the 
                    <input type="submit" name="loadscripts" class="button-primary" value="Load Scripts" /> button.<br /><br />
                    <em>Note: Load Scripts button will not work if <code>file_get_contents()</code> is disabled on your server.</em>
                <?php endif; ?>
            </div>
            <div id="tabs-2">
                <?php if (!empty($registered_styles->queue)): ?>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>Style Sheet</th>
                                <th>Logic</th>
                                <th><input type="submit" name="submit" value="Save Settings" class="button-primary right"  /></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3"><input type="submit" name="submit" value="Save Settings" class="button-primary"  /></th>
                            </tr>
                        </tfoot>
                        <?php
                        foreach ($registered_styles->queue as $handle) :
                            $i++;
                            ?>
                            <tr>
                                <td>
                                    <label for="sb_script<?php echo $i; ?>">
                                        <b>handle:</b> <?php echo $handle; ?><br />
                                        <?php if (!empty($registered_styles->registered[$handle]->src)): ?><small><?php echo $registered_styles->registered[$handle]->src; ?></small><?php endif; ?>
                                    </label>
                                </td>
                                <td>
                                    <textarea style="width:210px; height:70px;" id="sb_script<?php echo $i; ?>" name="sb_scriptlogic[style][<?php echo $handle ?>]"><?php echo !empty($sb_settings['style'][$handle])? $sb_settings['style'][$handle]: ""; ?></textarea>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    No style sheets loaded yet, reload your website's home page <a target="_blank" href=" <?php echo get_home_url(); ?> "> by clicking here</a> or hit the
                    <input type="submit" name="loadscripts" class="button-primary" value="Load Style Sheets" /> button.<br /><br />
                    <em>Note: Load Style Sheets button will not work if <code>file_get_contents()</code> is disabled on your server.</em>
                <?php endif; ?>
            </div>
            <div id="tabs-3">
                This plugin lists all JavaScripts and Style sheets with a control field that lets you control CSS & JavaScript files to include only on the pages where you actually need them. The text field lets you use WP's <a href="http://codex.wordpress.org/Conditional_Tags" target="_blank" >Conditional Tags</a>, or any general PHP code. <br /><br />
                
                <h2>Usage Example:</h2>
                Lets suppose you are using Contact Form 7 plugin. You created a page Contact Us (slug = contact-us) and want to include Contact Form 7 scripts only on this page. 
                <br /><br />
                <h3>Step 1:</h3><br />
                Find the Contact Form 7 JS and CSS on plugin's configuration page (they have handle = contact-form-7).<br />
                <h3>Step 2:</h3><br />
                Put <code>is_page('contact-us')</code> in logic field <br /><br />
                <img src="<?php echo plugins_url('images/cf7-js.png', __FILE__);?>" alt="Script Logic" /><br />
                <img src="<?php echo plugins_url('images/cf7-css.png', __FILE__);?>" alt="Script Logic" /><br />
                <h3>Step 3:</h3><br />
                Dance! You just excluded 2 files from all pages except your contact us page making your site load faster!!! <br /><br /><br />
				<a href="http://wordpress.org/support/view/plugin-reviews/script-logic#postform" target="_blank">Please rate the Plugin</a>
            </div>
        </div>
    </form>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery( "#tabs" ).tabs();
    });
</script>