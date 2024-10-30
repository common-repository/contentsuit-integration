<div id="wpbody-content" aria-label="<?php __($this->plugin, 'contentsuit-integration'); ?>" tabindex="0">
  <div class="wrap">
    <h2><?php _e($this->plugin, 'contentsuit-integration'); ?></h2>
    <div class="tags-responses">
      <?php if ($this->options['css']): ?>
        <div class="notice notice-success">
          <p><?php _e($this->params['csstags'], 'contentsuit-integration'); ?></p>
        </div>
      <?php endif; ?>
      <div class="clear"></div>
      <?php if ($this->options['js']): ?>
        <div class="notice notice-success">  
          <p><?php _e($this->params['jstags'], 'contentsuit-integration'); ?></p>
        </div>
        <div class="clear"></div>
      <?php elseif ($this->options['key'] && count($this->options < 2)): ?>
        <div class="notice notice-error">  
          <p><?php _e($this->params['notags'], 'contentsuit-integration'); ?></p>
        </div>
      <?php endif; ?>
    </div>
    <div class="postbox">
      <div class="inside">
        <form method="post" class="contentsuit-form">
          <div class="label">
            <label><?php _e($this->params['label'], 'contentsuit-integration'); ?></label>
          </div>
          <div class="inputs">
            <br>
            <input name="contentsuit[key]" type="text" class="menu-name regular-text menu-item-textbox" maxlength="8" value="<?php echo $this->options['key']; ?>" placeholder="xXxXxXxX"/>
            <button type="submit" name="contentsuit[submit]" id="submit" class="button button-primary">
              <?php if (!$this->options['key']): ?>
                <span><?php _e($this->params['save'], 'contentsuit-integration'); ?></span>
              <?php else: ?>
                <span><?php _e($this->params['update'], 'contentsuit-integration'); ?></span>
              <?php endif; ?>
            </button>
          </div>
          <div class="content">
            <p>
              <strong><?php _e($this->params['intro'], 'contentsuit-integration'); ?></strong>
              <br><span><?php _e($this->params['content'], 'contentsuit-integration'); ?></span>
              <a target="_blank" href="https://www.contentsuit.com/request/features?ref=plugin-wp">
                <span><?php _e($this->params['request'], 'contentsuit-integration'); ?></span>
              </a>.
            </p>
            <p>
              <strong><?php _e($this->params['problem'], 'contentsuit-integration'); ?></strong>
              <br><span><?php _e($this->params['solution'], 'contentsuit-integration'); ?></span>
              <a target="_blank" href="mailto:<?php echo $this->params['email']; ?>">
                <span><?php _e($this->params['email'], 'contentsuit-integration'); ?></span>
              </a>.
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>