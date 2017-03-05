<div class="wrap">

    <?php include_once(WPGlassdoor::PATH . '/view/_header.php'); ?>

    <div id="poststuff" class="metabox-holder">

        <?php include_once(WPGlassdoor::PATH . '/view/_messages.php'); ?>

        <div id="post-body" class="has-sidebar">

            <div id="post-body-content" class="has-sidebar-content">

                <div id='normal-sortables' class='meta-box-sortables'>

                    <div class="postbox " >
                        <h3 class='hndle' style='cursor:default;'>
                            <span style='vertical-align: top;'>Glassdoor Widget Settings</span>
                        </h3>
                        <div class="inside">
                            <div class="notes">
                                <p>Default widget settings.</p>
                            </div>
                            <form method="post" enctype="multipart/form-data">
                                <table>
                                    <colgroup>
                                        <col width="25%" />
                                        <col width="100%" />
                                    </colgroup>
                                    <tbody>
                                        <tr><td>Tracking ID:</td><td><input type="text" name="settings[tracking_id]" value="<?php echo $settings['tracking_id']; ?>" class="center" style="width:100px"></td></tr>
                                        <tr><td>Widget Size (W pixels x H pixels):</td><td><input type="text" name="settings[widget_width]" value="<?php echo $settings['widget_width']; ?>" style="width:100px" class="center">&nbsp;&nbsp;x&nbsp;&nbsp;<input type="text" name="settings[widget_height]" value="<?php echo $settings['widget_height']; ?>" style="width:100px" class="center"></td></tr>
                                        <tr><td>Widget Chart:</td><td><input type="radio" name="settings[widget_chart]" value="1" <?php echo $settings['widget_chart'] ? 'checked="checked"' : ''; ?>>&nbsp;Yes&nbsp;&nbsp;<input type="radio" name="settings[widget_chart]" value="0" <?php echo !$settings['widget_chart'] ? 'checked="checked"' : ''; ?>>&nbsp;No</td></td></tr>
                                    </tbody>
                                </table>
                                <table style="width:100%">
                                    <tbody>
                                        <tr><td colspan="2" style="text-align:right;"><input type="submit" class="button" name="save_settings" value="Save"></td></tr>
                                    </tbody>
                                </table>
                            </form>
                         </div>
                    </div>

                </div>

            </div> <!-- class="has-sidebar-content" -->

        </div> <!-- class="has-sidebar" -->

    </div> <!-- class="metabox-holder" -->

</div> <!-- class="wrap" -->

<?php include_once(WPGlassdoor::PATH . '/view/_footer.php'); ?>