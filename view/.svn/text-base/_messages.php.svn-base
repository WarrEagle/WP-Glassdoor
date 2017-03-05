    <div id="flash-messages">
    <?php
    if (isset(WPGlassdoor::$flash_messages) and is_array(WPGlassdoor::$flash_messages)) {
        foreach(WPGlassdoor::$flash_messages as $idx=>$message) {
            if ($message['display'] == 'local') {
                switch ($message['type']) {
                    case WPGlassdoor::MSG_ERROR:
                        ?>
                        <div class="wpgd-msg-error">
                            <span class="ui-icon ui-icon-alert" style="float:left"></span>
                            &nbsp;<strong>ERROR:</strong>
                            <?php echo $message['text']; ?>
                            <span class="ui-icon ui-icon-closethick close-message" style="float:right" title="Close message"></span>
                        </div>
                        <?php
                        break;
                    case WPGlassdoor::MSG_WARNING:
                        ?>
                        <div class="wpgd-msg-warning">
                            <span class="ui-icon ui-icon-alert" style="float:left"></span>
                            &nbsp;<strong>WARNING:</strong>
                            <?php echo $message['text']; ?>
                            <span class="ui-icon ui-icon-closethick close-message" style="float:right" title="Close message"></span>
                        </div>
                        <?php
                        break;
                    case WPGlassdoor::MSG_SUCCESS:
                        ?>
                        <div class="wpgd-msg-success">
                            <span class="ui-icon ui-icon-circle-check" style="float:left"></span>
                            &nbsp;<strong>SUCCESS:</strong>
                            <?php echo $message['text']; ?>
                            <span class="ui-icon ui-icon-closethick close-message" style="float:right" title="Close message"></span>
                        </div>
                        <?php
                        break;
                    case WPGlassdoor::MSG_INFO:
                    default:
                        ?>
                        <div class="wpgd-msg-info">
                            <span class="ui-icon ui-icon-info" style="float:left"></span>
                            &nbsp;
                            <?php echo $message['text']; ?>
                            <span class="ui-icon ui-icon-closethick close-message" style="float:right" title="Close message"></span>
                        </div>
                        <?php
                        break;
                }
            }
        }
    }
    ?>
    </div>
