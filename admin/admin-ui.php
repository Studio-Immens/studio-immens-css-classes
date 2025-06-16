<div class="si-css-admin wrap">
    <h1 class="si-title"><?php _e('Studio Immens CSS Classes', 'studioimmens-css'); ?></h1>
    
    <div class="si-form-container">
        <div class="si-form-column">
            <div class="si-form-box">
                <h2><?php _e('Add New CSS Class', 'studioimmens-css'); ?> <input type="button" id="clear" class="immens-btn" value="Clear"> </h2>
                
                <form id="si-css-form">
                    <input type="hidden" id="edit-or-not" value="no">
                    <input type="hidden" id="class-id">
                    <div class="si-form-group">
                        <label for="class-name"><?php _e('Class Name', 'studioimmens-css'); ?></label>
                        <input type="text" id="class-name" required>
                        <small><?php _e('Without dot (e.g. "my-class")', 'studioimmens-css'); ?></small>
                    </div>
                    
                    <div class="si-form-group">
                        <label for="class-css"><?php _e('Class Code', 'studioimmens-css'); ?></label>
                        <textarea id="class-css" rows="5" required></textarea>
                    </div>

                    <div class="si-form-group">
                        <label for="hover-css"><?php _e('Class :hover', 'studioimmens-css'); ?></label>
                        <textarea id="hover-css" rows="5"></textarea>
                    </div>

                    <div class="si-form-group">
                        <label for="focus-css"><?php _e('Class :focus', 'studioimmens-css'); ?></label>
                        <textarea id="focus-css" rows="5"></textarea>
                    </div>
                    
                    <button type="submit" class="button button-primary button-large">
                        <?php _e('Save Class', 'studioimmens-css'); ?>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="si-preview-column">
            <div class="si-preview-box">
                <h2><?php _e('Live Preview', 'studioimmens-css'); ?></h2>
                <div class="si-preview-controls">
                    <label>
                        <input type="checkbox" id="si-live-preview" checked>
                        <?php _e('Enable Live Preview', 'studioimmens-css');?>
                    </label>
                    <input type="button" id="remove-class" class="immens-btn" value="Clear">
                </div>
                <div id="si-dummy-content" class="si-dummy-content">
                    <?php _e('Lorem Ipsum is simply dummy text of <br>the printing and typesetting industry.', 'studioimmens-css'); ?><br>
                    <div>div Element</div>
                    <p>p Element</p>
                    <i>i Element</i>
                    <b>b Element</b>
                    <span>span Element</span>
                    <strong>strong Element</strong>
                    <button>button Element</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="si-classes-list">
        <h2><?php _e('Your CSS Classes', 'studioimmens-css'); ?></h2>
        
        <div class="si-list-controls">
            <div class="si-search-box">
                <input type="text" id="si-class-search" placeholder="<?php _e('Search classes...', 'studioimmens-css'); ?>">
            </div>
            <div class="si-stats">
                <span id="si-classes-count">0</span> <?php _e('classes', 'studioimmens-css'); ?>
            </div>
        </div>
        
        <div id="si-classes-container" class="si-classes-grid">
            <!-- Le classi verranno caricate qui dinamicamente -->
        </div>
    </div>
</div>