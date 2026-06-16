<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SICC_Pack_Manager {

    private static $option_packs = 'sicc_css_packs';
    private static $option_classes = 'sicc_css_classes';
    private static $presets_version = '2.3';

    public function __construct() {
        add_action( 'init', [ $this, 'maybe_load_presets' ] );
    }

    public function maybe_load_presets() {
        $packs = get_option( self::$option_packs, [] );
        $saved_version = get_option( 'sicc_css_presets_version', '' );
        if ( ! empty( $packs ) && $saved_version === self::$presets_version ) {
            return;
        }

        $presets = [
            'layout-pack' => [
                'slug' => 'layout-pack',
                'name' => __('Layout Pack', 'studio-immens-css-classes'),
                'author' => __('Studio Immens', 'studio-immens-css-classes'),
                'version' => '1.0',
                'description' => __('Utility classes for display, flexbox, grid and positioning.', 'studio-immens-css-classes'),
                'classes' => [
                    ['name' => 'sicc-d-flex', 'css' => 'display: flex;', 'description' => __('Creates a flexible container', 'studio-immens-css-classes')],
                    ['name' => 'sicc-d-inline-flex', 'css' => 'display: inline-flex;', 'description' => __('Creates an inline flexible container', 'studio-immens-css-classes')],
                    ['name' => 'sicc-d-grid', 'css' => 'display: grid;', 'description' => __('Creates a grid container', 'studio-immens-css-classes')],
                    ['name' => 'sicc-d-block', 'css' => 'display: block;', 'description' => __('Displays as a block element', 'studio-immens-css-classes')],
                    ['name' => 'sicc-d-inline-block', 'css' => 'display: inline-block;', 'description' => __('Displays as an inline block', 'studio-immens-css-classes')],
                    ['name' => 'sicc-d-none', 'css' => 'display: none;', 'description' => __('Hides the element completely', 'studio-immens-css-classes')],
                    ['name' => 'sicc-flex-row', 'css' => 'display: flex; flex-direction: row;', 'description' => __('Flex items in horizontal row', 'studio-immens-css-classes')],
                    ['name' => 'sicc-flex-col', 'css' => 'display: flex; flex-direction: column;', 'description' => __('Flex items in vertical column', 'studio-immens-css-classes')],
                    ['name' => 'sicc-flex-wrap', 'css' => 'display: flex; flex-wrap: wrap;', 'description' => __('Flex items wrap to next line', 'studio-immens-css-classes')],
                    ['name' => 'sicc-flex-center', 'css' => 'display: flex; align-items: center; justify-content: center;', 'description' => __('Centers content on both axes', 'studio-immens-css-classes')],
                    ['name' => 'sicc-flex-between', 'css' => 'display: flex; align-items: center; justify-content: space-between;', 'description' => __('Distributes items with space between', 'studio-immens-css-classes')],
                    ['name' => 'sicc-flex-1', 'css' => 'flex: 1;', 'description' => __('Takes all available space', 'studio-immens-css-classes')],
                    ['name' => 'sicc-gap-sm', 'css' => 'gap: 8px;', 'description' => __('Small gap between flex/grid items', 'studio-immens-css-classes')],
                    ['name' => 'sicc-gap-md', 'css' => 'gap: 16px;', 'description' => __('Medium gap between flex/grid items', 'studio-immens-css-classes')],
                    ['name' => 'sicc-gap-lg', 'css' => 'gap: 32px;', 'description' => __('Large gap between flex/grid items', 'studio-immens-css-classes')],
                    ['name' => 'sicc-grid-cols-2', 'css' => 'display: grid; grid-template-columns: repeat(2, 1fr);', 'description' => __('Grid with 2 equal columns', 'studio-immens-css-classes')],
                    ['name' => 'sicc-grid-cols-3', 'css' => 'display: grid; grid-template-columns: repeat(3, 1fr);', 'description' => __('Grid with 3 equal columns', 'studio-immens-css-classes')],
                    ['name' => 'sicc-relative', 'css' => 'position: relative;', 'description' => __('Relative positioning (reference for absolute children)', 'studio-immens-css-classes')],
                    ['name' => 'sicc-absolute', 'css' => 'position: absolute;', 'description' => __('Absolute positioning relative to container', 'studio-immens-css-classes')],
                    ['name' => 'sicc-overflow-hidden', 'css' => 'overflow: hidden;', 'description' => __('Hides overflowing content', 'studio-immens-css-classes')],
                    ['name' => 'sicc-overflow-auto', 'css' => 'overflow: auto;', 'description' => __('Shows scrollbars when needed', 'studio-immens-css-classes')],
                    ['name' => 'sicc-z-1', 'css' => 'z-index: 1;', 'description' => __('Brings to front (level 1)', 'studio-immens-css-classes')],
                    ['name' => 'sicc-z-10', 'css' => 'z-index: 10;', 'description' => __('Brings to front (level 10)', 'studio-immens-css-classes')],
                ]
            ],
            'spacing-pack' => [
                'slug' => 'spacing-pack',
                'name' => __('Spacing Pack', 'studio-immens-css-classes'),
                'author' => __('Studio Immens', 'studio-immens-css-classes'),
                'version' => '1.0',
                'description' => __('Utility classes for margin, padding, width, height and gaps.', 'studio-immens-css-classes'),
                'classes' => [
                    ['name' => 'sicc-m-0', 'css' => 'margin: 0;', 'description' => __('Removes all margins', 'studio-immens-css-classes')],
                    ['name' => 'sicc-m-2', 'css' => 'margin: 8px;', 'description' => __('8px margin on all sides', 'studio-immens-css-classes')],
                    ['name' => 'sicc-m-4', 'css' => 'margin: 16px;', 'description' => __('16px margin on all sides', 'studio-immens-css-classes')],
                    ['name' => 'sicc-m-6', 'css' => 'margin: 32px;', 'description' => __('32px margin on all sides', 'studio-immens-css-classes')],
                    ['name' => 'sicc-mx-auto', 'css' => 'margin-left: auto; margin-right: auto;', 'description' => __('Centers block horizontally', 'studio-immens-css-classes')],
                    ['name' => 'sicc-mt-2', 'css' => 'margin-top: 8px;', 'description' => __('8px top margin', 'studio-immens-css-classes')],
                    ['name' => 'sicc-mt-4', 'css' => 'margin-top: 16px;', 'description' => __('16px top margin', 'studio-immens-css-classes')],
                    ['name' => 'sicc-mb-2', 'css' => 'margin-bottom: 8px;', 'description' => __('8px bottom margin', 'studio-immens-css-classes')],
                    ['name' => 'sicc-mb-4', 'css' => 'margin-bottom: 16px;', 'description' => __('16px bottom margin', 'studio-immens-css-classes')],
                    ['name' => 'sicc-ml-auto', 'css' => 'margin-left: auto;', 'description' => __('Pushes element to the right', 'studio-immens-css-classes')],
                    ['name' => 'sicc-p-0', 'css' => 'padding: 0;', 'description' => __('Removes all padding', 'studio-immens-css-classes')],
                    ['name' => 'sicc-p-2', 'css' => 'padding: 8px;', 'description' => __('8px padding on all sides', 'studio-immens-css-classes')],
                    ['name' => 'sicc-p-4', 'css' => 'padding: 16px;', 'description' => __('16px padding on all sides', 'studio-immens-css-classes')],
                    ['name' => 'sicc-px-2', 'css' => 'padding-left: 8px; padding-right: 8px;', 'description' => __('8px horizontal padding', 'studio-immens-css-classes')],
                    ['name' => 'sicc-px-4', 'css' => 'padding-left: 16px; padding-right: 16px;', 'description' => __('16px horizontal padding', 'studio-immens-css-classes')],
                    ['name' => 'sicc-py-2', 'css' => 'padding-top: 8px; padding-bottom: 8px;', 'description' => __('8px vertical padding', 'studio-immens-css-classes')],
                    ['name' => 'sicc-py-4', 'css' => 'padding-top: 16px; padding-bottom: 16px;', 'description' => __('16px vertical padding', 'studio-immens-css-classes')],
                    ['name' => 'sicc-pt-4', 'css' => 'padding-top: 16px;', 'description' => __('16px top padding', 'studio-immens-css-classes')],
                    ['name' => 'sicc-pb-4', 'css' => 'padding-bottom: 16px;', 'description' => __('16px bottom padding', 'studio-immens-css-classes')],
                    ['name' => 'sicc-w-full', 'css' => 'width: 100%;', 'description' => __('100% width of container', 'studio-immens-css-classes')],
                    ['name' => 'sicc-w-auto', 'css' => 'width: auto;', 'description' => __('Automatic width based on content', 'studio-immens-css-classes')],
                    ['name' => 'sicc-w-half', 'css' => 'width: 50%;', 'description' => __('50% width of container', 'studio-immens-css-classes')],
                    ['name' => 'sicc-h-full', 'css' => 'height: 100%;', 'description' => __('100% height of container', 'studio-immens-css-classes')],
                    ['name' => 'sicc-h-auto', 'css' => 'height: auto;', 'description' => __('Automatic height based on content', 'studio-immens-css-classes')],
                    ['name' => 'sicc-h-screen', 'css' => 'height: 100vh;', 'description' => __('Full viewport height', 'studio-immens-css-classes')],
                    ['name' => 'sicc-max-w-sm', 'css' => 'max-width: 540px;', 'description' => __('Small container max-width 540px', 'studio-immens-css-classes')],
                    ['name' => 'sicc-max-w-md', 'css' => 'max-width: 720px;', 'description' => __('Medium container max-width 720px', 'studio-immens-css-classes')],
                    ['name' => 'sicc-max-w-lg', 'css' => 'max-width: 960px;', 'description' => __('Large container max-width 960px', 'studio-immens-css-classes')],
                ]
            ],
            'typography-pack' => [
                'slug' => 'typography-pack',
                'name' => __('Typography Pack', 'studio-immens-css-classes'),
                'author' => __('Studio Immens', 'studio-immens-css-classes'),
                'version' => '1.0',
                'description' => __('Utility classes for typography: alignment, weight, size and decoration.', 'studio-immens-css-classes'),
                'classes' => [
                    ['name' => 'sicc-text-left', 'css' => 'text-align: left;', 'description' => __('Aligns text to the left', 'studio-immens-css-classes')],
                    ['name' => 'sicc-text-center', 'css' => 'text-align: center;', 'description' => __('Centers text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-text-right', 'css' => 'text-align: right;', 'description' => __('Aligns text to the right', 'studio-immens-css-classes')],
                    ['name' => 'sicc-font-light', 'css' => 'font-weight: 300;', 'description' => __('Light font weight', 'studio-immens-css-classes')],
                    ['name' => 'sicc-font-normal', 'css' => 'font-weight: 400;', 'description' => __('Normal font weight', 'studio-immens-css-classes')],
                    ['name' => 'sicc-font-bold', 'css' => 'font-weight: 700;', 'description' => __('Bold text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-text-sm', 'css' => 'font-size: 0.875rem;', 'description' => __('Small text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-text-base', 'css' => 'font-size: 1rem;', 'description' => __('Default text size', 'studio-immens-css-classes')],
                    ['name' => 'sicc-text-lg', 'css' => 'font-size: 1.125rem;', 'description' => __('Large text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-text-xl', 'css' => 'font-size: 1.25rem;', 'description' => __('Extra large text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-text-2xl', 'css' => 'font-size: 1.5rem;', 'description' => __('Double large text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-uppercase', 'css' => 'text-transform: uppercase;', 'description' => __('Converts text to UPPERCASE', 'studio-immens-css-classes')],
                    ['name' => 'sicc-lowercase', 'css' => 'text-transform: lowercase;', 'description' => __('Converts text to lowercase', 'studio-immens-css-classes')],
                    ['name' => 'sicc-capitalize', 'css' => 'text-transform: capitalize;', 'description' => __('Capitalizes first letter of each word', 'studio-immens-css-classes')],
                    ['name' => 'sicc-underline', 'css' => 'text-decoration: underline;', 'description' => __('Underlines text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-line-through', 'css' => 'text-decoration: line-through;', 'description' => __('Strikethrough text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-no-underline', 'css' => 'text-decoration: none;', 'description' => __('Removes underline from text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-italic', 'css' => 'font-style: italic;', 'description' => __('Italic text', 'studio-immens-css-classes')],
                    ['name' => 'sicc-truncate', 'css' => 'overflow: hidden; text-overflow: ellipsis; white-space: nowrap;', 'description' => __('Truncates overflowing text with ellipsis', 'studio-immens-css-classes')],
                    ['name' => 'sicc-leading-tight', 'css' => 'line-height: 1.25;', 'description' => __('Tight line height', 'studio-immens-css-classes')],
                    ['name' => 'sicc-leading-normal', 'css' => 'line-height: 1.5;', 'description' => __('Normal line height', 'studio-immens-css-classes')],
                    ['name' => 'sicc-leading-relaxed', 'css' => 'line-height: 1.75;', 'description' => __('Relaxed line height', 'studio-immens-css-classes')],
                    ['name' => 'sicc-whitespace-nowrap', 'css' => 'white-space: nowrap;', 'description' => __('Prevents text from wrapping', 'studio-immens-css-classes')],
                    ['name' => 'sicc-link-style', 'css' => 'color: #2271b1; text-decoration: underline; cursor: pointer;', 'hover' => 'color: #135e96;', 'description' => __('Link styling with underline', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-underline', 'css' => 'text-decoration: none;', 'hover' => 'text-decoration: underline;', 'description' => __('Underline on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-color-primary', 'css' => 'transition: color 0.2s ease;', 'hover' => 'color: #2271b1;', 'description' => __('Primary color on hover', 'studio-immens-css-classes')],
                ]
            ],
            'effects-pack' => [
                'slug' => 'effects-pack',
                'name' => __('Effects Pack', 'studio-immens-css-classes'),
                'author' => __('Studio Immens', 'studio-immens-css-classes'),
                'version' => '1.0',
                'description' => __('Utility classes for shadows, opacity, borders and rounded corners.', 'studio-immens-css-classes'),
                'classes' => [
                    ['name' => 'sicc-shadow-sm', 'css' => 'box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);', 'hover' => 'box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);', 'focus' => 'box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);', 'active' => 'box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);', 'description' => __('Small subtle shadow', 'studio-immens-css-classes')],
                    ['name' => 'sicc-shadow', 'css' => 'box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1);', 'hover' => 'box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);', 'focus' => 'box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);', 'description' => __('Default shadow', 'studio-immens-css-classes')],
                    ['name' => 'sicc-shadow-md', 'css' => 'box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);', 'hover' => 'box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);', 'description' => __('Medium shadow', 'studio-immens-css-classes')],
                    ['name' => 'sicc-shadow-lg', 'css' => 'box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);', 'description' => __('Large prominent shadow', 'studio-immens-css-classes')],
                    ['name' => 'sicc-shadow-none', 'css' => 'box-shadow: none;', 'description' => __('Removes any shadow', 'studio-immens-css-classes')],
                    ['name' => 'sicc-opacity-0', 'css' => 'opacity: 0;', 'focus' => 'opacity: 0.3;', 'description' => __('Makes element completely transparent', 'studio-immens-css-classes')],
                    ['name' => 'sicc-opacity-50', 'css' => 'opacity: 0.5;', 'hover' => 'opacity: 0.8;', 'focus' => 'opacity: 0.8;', 'active' => 'opacity: 0.3;', 'description' => __('Makes element semi-transparent', 'studio-immens-css-classes')],
                    ['name' => 'sicc-opacity-100', 'css' => 'opacity: 1;', 'hover' => 'opacity: 0.8;', 'description' => __('Makes element fully opaque', 'studio-immens-css-classes')],
                    ['name' => 'sicc-rounded', 'css' => 'border-radius: 0.25rem;', 'hover' => 'border-radius: 0.375rem;', 'description' => __('Slightly rounded corners', 'studio-immens-css-classes')],
                    ['name' => 'sicc-rounded-md', 'css' => 'border-radius: 0.375rem;', 'hover' => 'border-radius: 0.5rem;', 'description' => __('Moderately rounded corners', 'studio-immens-css-classes')],
                    ['name' => 'sicc-rounded-lg', 'css' => 'border-radius: 0.5rem;', 'hover' => 'border-radius: 0.75rem;', 'description' => __('Very rounded corners', 'studio-immens-css-classes')],
                    ['name' => 'sicc-rounded-full', 'css' => 'border-radius: 9999px;', 'hover' => 'border-radius: 9999px; transform: scale(1.05);', 'description' => __('Fully rounded corners (circle)', 'studio-immens-css-classes')],
                    ['name' => 'sicc-border', 'css' => 'border: 1px solid #e2e8f0; transition: border-color 0.2s ease, box-shadow 0.2s ease;', 'hover' => 'border-color: #2271b1;', 'focus' => 'border-color: #2271b1; box-shadow: 0 0 0 2px rgba(34,113,177,0.15);', 'active' => 'border-color: #135e96;', 'visited' => 'border-color: #7c3aed;', 'description' => __('Thin light gray border', 'studio-immens-css-classes')],
                    ['name' => 'sicc-border-2', 'css' => 'border: 2px solid #e2e8f0; transition: border-color 0.2s ease;', 'hover' => 'border-color: #2271b1;', 'focus' => 'border-color: #2271b1;', 'active' => 'border-color: #135e96;', 'description' => __('Medium light gray border', 'studio-immens-css-classes')],
                    ['name' => 'sicc-border-0', 'css' => 'border: 0;', 'description' => __('Removes border', 'studio-immens-css-classes')],
                    ['name' => 'sicc-border-none', 'css' => 'border: none;', 'description' => __('No border', 'studio-immens-css-classes')],
                    ['name' => 'sicc-cursor-pointer', 'css' => 'cursor: pointer;', 'hover' => 'opacity: 0.85;', 'description' => __('Changes cursor to hand (clickable)', 'studio-immens-css-classes')],
                    ['name' => 'sicc-transition', 'css' => 'transition: all 0.2s ease;', 'description' => __('Smooth transition on all properties', 'studio-immens-css-classes')],
                    ['name' => 'sicc-scale-110', 'css' => 'transform: scale(1.1);', 'hover' => 'transform: scale(1.2);', 'active' => 'transform: scale(0.95);', 'description' => __('Scales element up 10%', 'studio-immens-css-classes')],
                    ['name' => 'sicc-rotate-90', 'css' => 'transform: rotate(90deg);', 'hover' => 'transform: rotate(95deg);', 'description' => __('Rotates element 90 degrees', 'studio-immens-css-classes')],
                    ['name' => 'sicc-rounded-sm', 'css' => 'border-radius: 0.125rem;', 'hover' => 'border-radius: 0.25rem;', 'description' => __('Slight rounded corners', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-shadow', 'css' => 'transition: box-shadow 0.2s ease;', 'hover' => 'box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);', 'description' => __('Elevates shadow on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-scale', 'css' => 'transition: transform 0.2s ease;', 'hover' => 'transform: scale(1.05);', 'description' => __('Scales up slightly on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-lift', 'css' => 'transition: transform 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);', 'hover' => 'transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.15);', 'description' => __('Lifts with shadow on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-bright', 'css' => 'transition: filter 0.2s ease;', 'hover' => 'filter: brightness(1.15);', 'description' => __('Increases brightness on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-opacity', 'css' => 'transition: opacity 0.2s ease;', 'hover' => 'opacity: 0.8;', 'description' => __('Decreases opacity on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-color', 'css' => 'transition: color 0.2s ease;', 'hover' => 'color: #2271b1;', 'description' => __('Changes to primary color on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-bg', 'css' => 'transition: background 0.2s ease;', 'hover' => 'background: #f0f6fc;', 'description' => __('Light blue background on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-rotate', 'css' => 'transition: transform 0.3s ease;', 'hover' => 'transform: rotate(3deg);', 'description' => __('Slight rotation on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-ring', 'css' => '', 'focus' => 'outline: 2px solid #2271b1; outline-offset: 2px;', 'description' => __('Visible focus ring when selected', 'studio-immens-css-classes')],
                ]
            ],
            'interactive-pack' => [
                'slug' => 'interactive-pack',
                'name' => __('Interactive Effects', 'studio-immens-css-classes'),
                'author' => __('Studio Immens', 'studio-immens-css-classes'),
                'version' => '1.0',
                'description' => __('Utility classes for hover, focus, active and visited states: glow, border, contrast and interactive feedback.', 'studio-immens-css-classes'),
                'classes' => [
                    ['name' => 'sicc-hover-glow', 'css' => 'transition: box-shadow 0.3s ease;', 'hover' => 'box-shadow: 0 0 18px rgba(34,113,177,0.4);', 'description' => __('Glow effect on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-border', 'css' => 'border: 1px solid #e2e8f0; transition: border-color 0.3s ease, box-shadow 0.3s ease;', 'hover' => 'border-color: #2271b1; box-shadow: 0 0 0 2px rgba(34,113,177,0.15);', 'description' => __('Highlighted border on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-sink', 'css' => 'transition: transform 0.3s ease;', 'hover' => 'transform: translateY(3px) scaleY(0.97);', 'description' => __('Press down effect on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-skew', 'css' => 'transition: transform 0.3s ease;', 'hover' => 'transform: skewX(-3deg);', 'description' => __('Skew on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-float', 'css' => 'transition: transform 0.3s ease;', 'hover' => 'transform: translateY(-3px);', 'description' => __('Gentle float on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-bg-dark', 'css' => 'transition: background 0.3s ease;', 'hover' => 'background: rgba(0,0,0,0.05);', 'description' => __('Dark background on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-underline-center', 'css' => 'position: relative; display: inline-block;', 'hover' => '', 'description' => __('Underline from center on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-contrast', 'css' => 'transition: filter 0.3s ease;', 'hover' => 'filter: contrast(1.2);', 'description' => __('Increased contrast on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-saturate', 'css' => 'transition: filter 0.3s ease;', 'hover' => 'filter: saturate(1.3);', 'description' => __('Saturated colors on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-sepia', 'css' => 'transition: filter 0.3s ease;', 'hover' => 'filter: sepia(0.4);', 'description' => __('Sepia tone on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-outline', 'css' => 'transition: outline-offset 0.2s ease, outline-color 0.2s ease; outline: 2px solid transparent; outline-offset: 2px;', 'hover' => 'outline-color: #2271b1; outline-offset: 4px;', 'description' => __('Animated outline on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-weight', 'css' => 'transition: font-weight 0.2s ease;', 'hover' => 'font-weight: 700;', 'description' => __('Bold text on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-expand', 'css' => 'transition: padding 0.3s ease;', 'hover' => 'padding: 4px 8px;', 'description' => __('Expand on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-rotate-3d', 'css' => 'transition: transform 0.3s ease; transform-style: preserve-3d;', 'hover' => 'transform: perspective(400px) rotateX(10deg);', 'description' => __('3D rotation on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-hover-color-alt', 'css' => 'transition: color 0.2s ease;', 'hover' => 'color: #7c3aed;', 'description' => __('Purple text color on hover', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-shadow', 'css' => 'transition: box-shadow 0.2s ease;', 'focus' => 'box-shadow: 0 0 0 3px rgba(34,113,177,0.25);', 'description' => __('Focus ring with shadow', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-border', 'css' => 'border: 1px solid #e2e8f0; transition: border-color 0.2s ease, box-shadow 0.2s ease;', 'focus' => 'border-color: #2271b1; box-shadow: 0 0 0 2px rgba(34,113,177,0.2);', 'description' => __('Focused border highlight', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-scale', 'css' => 'transition: transform 0.2s ease;', 'focus' => 'transform: scale(1.02);', 'description' => __('Slight scale on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-underline', 'css' => 'text-decoration: none; transition: text-decoration 0.2s ease;', 'focus' => 'text-decoration: underline;', 'description' => __('Underline on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-bg', 'css' => 'transition: background 0.2s ease;', 'focus' => 'background: #f0f7ff;', 'description' => __('Light background on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-outline-thick', 'css' => 'transition: outline-offset 0.15s ease; outline: 3px solid transparent;', 'focus' => 'outline-color: #2271b1; outline-offset: 2px;', 'description' => __('Thick custom outline on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-ring-color', 'css' => 'transition: box-shadow 0.15s ease;', 'focus' => 'box-shadow: 0 0 0 3px rgba(124,58,237,0.3);', 'description' => __('Purple focus ring', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-brightness', 'css' => 'transition: filter 0.2s ease;', 'focus' => 'filter: brightness(1.1);', 'description' => __('Brighten on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-glow', 'css' => 'transition: box-shadow 0.2s ease;', 'focus' => 'box-shadow: 0 0 12px rgba(34,113,177,0.3);', 'description' => __('Soft glow on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-expand', 'css' => 'transition: padding 0.2s ease, margin 0.2s ease;', 'focus' => 'padding: 2px 4px; margin: -2px -4px;', 'description' => __('Expand slightly on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-color', 'css' => 'transition: color 0.2s ease;', 'focus' => 'color: #2271b1;', 'description' => __('Blue text on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-rotate', 'css' => 'transition: transform 0.2s ease;', 'focus' => 'transform: rotate(-1deg);', 'description' => __('Slight rotation on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-weight', 'css' => 'transition: font-weight 0.2s ease;', 'focus' => 'font-weight: 600;', 'description' => __('Heavier weight on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-inset', 'css' => 'transition: box-shadow 0.15s ease;', 'focus' => 'box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);', 'description' => __('Inset shadow on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-focus-slide', 'css' => 'transition: transform 0.2s ease;', 'focus' => 'transform: translateX(4px);', 'description' => __('Slide right on focus', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-scale-up', 'css' => 'transition: transform 0.1s ease;', 'active' => 'transform: scale(1.03);', 'description' => __('Scale up on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-rotate', 'css' => 'transition: transform 0.1s ease;', 'active' => 'transform: rotate(2deg);', 'description' => __('Rotate on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-brightness', 'css' => 'transition: filter 0.1s ease;', 'active' => 'filter: brightness(0.9);', 'description' => __('Dim on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-outline', 'css' => 'transition: outline 0.1s ease; outline: 2px solid transparent;', 'active' => 'outline-color: #135e96;', 'description' => __('Outline on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-slide', 'css' => 'transition: transform 0.1s ease;', 'active' => 'transform: translateX(3px);', 'description' => __('Slide on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-float-reverse', 'css' => 'transition: transform 0.1s ease;', 'active' => 'transform: translateY(2px);', 'description' => __('Press down on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-weight', 'css' => 'transition: font-weight 0.1s ease;', 'active' => 'font-weight: 800;', 'description' => __('Bold weight on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-glow', 'css' => 'transition: box-shadow 0.1s ease;', 'active' => 'box-shadow: 0 0 10px rgba(34,113,177,0.3);', 'description' => __('Glow on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-skew', 'css' => 'transition: transform 0.1s ease;', 'active' => 'transform: skewX(2deg);', 'description' => __('Skew on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-border-inset', 'css' => 'border: 1px solid #e2e8f0; transition: border-color 0.1s ease, box-shadow 0.1s ease;', 'active' => 'border-color: #135e96; box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);', 'description' => __('Inset border on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-active-float-up', 'css' => 'transition: transform 0.1s ease;', 'active' => 'transform: translateY(-2px);', 'description' => __('Float up on active', 'studio-immens-css-classes')],
                    ['name' => 'sicc-visited-opacity', 'css' => '', 'visited' => 'opacity: 0.5;', 'description' => __('Lower opacity for visited links', 'studio-immens-css-classes')],
                    ['name' => 'sicc-visited-border', 'css' => 'border: 1px solid #e2e8f0; transition: border-color 0.2s ease;', 'visited' => 'border-color: #d8b4fe;', 'description' => __('Purple border for visited links', 'studio-immens-css-classes')],
                    ['name' => 'sicc-visited-bg', 'css' => '', 'visited' => 'background: #f5f3ff;', 'description' => __('Purple background for visited links', 'studio-immens-css-classes')],
                    ['name' => 'sicc-visited-underline', 'css' => 'text-decoration: none;', 'visited' => 'text-decoration: underline; text-decoration-color: #7c3aed;', 'description' => __('Purple underline for visited links', 'studio-immens-css-classes')],
                    ['name' => 'sicc-visited-strikethrough', 'css' => '', 'visited' => 'text-decoration: line-through;', 'description' => __('Strikethrough for visited links', 'studio-immens-css-classes')],
                    ['name' => 'sicc-visited-scale', 'css' => 'transition: transform 0.2s ease;', 'visited' => 'transform: scale(0.98);', 'description' => __('Slightly smaller for visited links', 'studio-immens-css-classes')],
                    ['name' => 'sicc-visited-small', 'css' => 'transition: font-size 0.2s ease;', 'visited' => 'font-size: 0.9em;', 'description' => __('Smaller text for visited links', 'studio-immens-css-classes')],
                    ['name' => 'sicc-visited-weight', 'css' => '', 'visited' => 'font-weight: 300;', 'description' => __('Lighter weight for visited links', 'studio-immens-css-classes')],
                ]
            ],
        ];

        foreach ( $presets as $slug => $data ) {
            $this->import_pack( $data );
        }

        update_option( 'sicc_css_presets_version', self::$presets_version );
        do_action( 'sicc_regenerate_css' );
    }

    public function import_pack( $data ) {
        $slug = sanitize_title( $data['slug'] );
        if ( empty( $slug ) || ! isset( $data['classes'] ) || ! is_array( $data['classes'] ) ) {
            return [ 'imported' => 0, 'updated' => 0, 'classes' => 0 ];
        }

        $existing_classes = get_option( self::$option_classes, [] );
        if ( ! is_array( $existing_classes ) ) {
            $existing_classes = [];
        }

        $imported = 0;
        $updated = 0;
        $class_count = 0;

        $existing_names = [];
        foreach ( $existing_classes as $cls ) {
            if ( isset( $cls['name'] ) ) {
                $existing_names[ $cls['name'] ] = true;
            }
        }

        foreach ( $data['classes'] as $class_data ) {
            if ( empty( $class_data['name'] ) ) {
                continue;
            }
            if ( empty( $class_data['css'] ) && empty( $class_data['hover'] ) && empty( $class_data['active'] ) && empty( $class_data['focus'] ) && empty( $class_data['visited'] ) ) {
                continue;
            }

            $class_name = sanitize_html_class( $class_data['name'] );
            if ( ! $class_name ) {
                continue;
            }

            $new_class = [
                'id'          => wp_generate_uuid4(),
                'name'        => $class_name,
                'css'         => ! empty( $class_data['css'] ) ? $this->sanitize_css_value( $class_data['css'] ) : '',
                'hover'       => isset( $class_data['hover'] ) ? $this->sanitize_css_value( $class_data['hover'] ) : '',
                'active'      => isset( $class_data['active'] ) ? $this->sanitize_css_value( $class_data['active'] ) : '',
                'focus'       => isset( $class_data['focus'] ) ? $this->sanitize_css_value( $class_data['focus'] ) : '',
                'visited'     => isset( $class_data['visited'] ) ? $this->sanitize_css_value( $class_data['visited'] ) : '',
                'description' => isset( $class_data['description'] ) ? sanitize_text_field( $class_data['description'] ) : '',
                'pack_slug'   => $slug,
            ];

            if ( isset( $existing_names[ $class_name ] ) ) {
                foreach ( $existing_classes as $k => $cls ) {
                    if ( isset( $cls['name'] ) && $cls['name'] === $class_name ) {
                        if ( ! empty( $cls['pack_slug'] ) ) {
                            $existing_classes[ $k ] = array_merge( $existing_classes[ $k ], $new_class );
                            $existing_classes[ $k ]['pack_slug'] = $slug;
                            $updated++;
                        }
                        break;
                    }
                }
            } else {
                $existing_classes[] = $new_class;
                $existing_names[ $class_name ] = true;
                $imported++;
            }
            $class_count++;
        }

        update_option( self::$option_classes, $existing_classes );

        $packs = get_option( self::$option_packs, [] );
        $packs[ $slug ] = [
            'slug'          => $slug,
            'name'          => sanitize_text_field( $data['name'] ?? $slug ),
            'author'        => sanitize_text_field( $data['author'] ?? 'Studio Immens' ),
            'version'       => sanitize_text_field( $data['version'] ?? '1.0' ),
            'description'   => sanitize_textarea_field( $data['description'] ?? '' ),
            'class_count'   => $class_count,
            'imported_at'   => time(),
        ];
        update_option( self::$option_packs, $packs );

        return [ 'imported' => $imported, 'updated' => $updated, 'classes' => $class_count ];
    }

    public function export_pack( $slug ) {
        $packs = get_option( self::$option_packs, [] );
        if ( ! isset( $packs[ $slug ] ) ) {
            return null;
        }

        $pack = $packs[ $slug ];
        $all_classes = get_option( self::$option_classes, [] );
        $pack_classes = [];

        foreach ( $all_classes as $cls ) {
            if ( isset( $cls['pack_slug'] ) && $cls['pack_slug'] === $slug ) {
                $entry = [
                    'name'  => $cls['name'],
                    'css'   => $cls['css'],
                ];
                if ( ! empty( $cls['description'] ) ) {
                    $entry['description'] = $cls['description'];
                }
                if ( ! empty( $cls['hover'] ) ) {
                    $entry['hover'] = $cls['hover'];
                }
                if ( ! empty( $cls['focus'] ) ) {
                    $entry['focus'] = $cls['focus'];
                }
                $pack_classes[] = $entry;
            }
        }

        return [
            'name'            => $pack['name'],
            'slug'            => $slug,
            'author'          => $pack['author'],
            'version'         => $pack['version'],
            'plugin_version'  => SI_CSS_CLASS_VERSION,
            'description'     => $pack['description'],
            'classes'         => $pack_classes,
            'exported_at'     => time(),
        ];
    }

    public function export_all() {
        $packs = get_option( self::$option_packs, [] );
        $all_classes = get_option( self::$option_classes, [] );

        $exported_at = time();
        $all_packs_data = [];

        foreach ( $packs as $slug => $pack ) {
            $pack_classes = [];
            foreach ( $all_classes as $cls ) {
                if ( isset( $cls['pack_slug'] ) && $cls['pack_slug'] === $slug ) {
                    $entry = [ 'name' => $cls['name'], 'css' => $cls['css'] ];
                    if ( ! empty( $cls['description'] ) ) $entry['description'] = $cls['description'];
                    if ( ! empty( $cls['hover'] ) ) $entry['hover'] = $cls['hover'];
                    if ( ! empty( $cls['focus'] ) ) $entry['focus'] = $cls['focus'];
                    $pack_classes[] = $entry;
                }
            }
            $all_packs_data[] = [
                'name'        => $pack['name'],
                'slug'        => $slug,
                'author'      => $pack['author'],
                'version'     => $pack['version'],
                'description' => $pack['description'],
                'classes'     => $pack_classes,
                'exported_at' => $exported_at,
            ];
        }

        return $all_packs_data;
    }

    public function delete_pack( $slug ) {
        $packs = get_option( self::$option_packs, [] );
        if ( ! isset( $packs[ $slug ] ) ) {
            return false;
        }

        unset( $packs[ $slug ] );
        update_option( self::$option_packs, $packs );

        $all_classes = get_option( self::$option_classes, [] );
        $remaining = [];

        foreach ( $all_classes as $cls ) {
            if ( isset( $cls['pack_slug'] ) && $cls['pack_slug'] === $slug ) {
                continue;
            }
            $remaining[] = $cls;
        }

        update_option( self::$option_classes, $remaining );
        do_action( 'sicc_regenerate_css' );

        return true;
    }

    public function create_pack( $name, $slug, $class_ids ) {
        if ( empty( $name ) || empty( $slug ) || empty( $class_ids ) ) {
            return false;
        }

        $slug = sanitize_title( $slug );
        $packs = get_option( self::$option_packs, [] );
        if ( isset( $packs[ $slug ] ) ) {
            return false;
        }

        $all_classes = get_option( self::$option_classes, [] );
        $class_count = 0;

        foreach ( $all_classes as $k => $cls ) {
            if ( in_array( $cls['id'], $class_ids ) ) {
                $all_classes[ $k ]['pack_slug'] = $slug;
                $class_count++;
            }
        }

        update_option( self::$option_classes, $all_classes );

        $packs[ $slug ] = [
            'slug'          => $slug,
            'name'          => sanitize_text_field( $name ),
            'author'        => sanitize_text_field( 'User' ),
            'version'       => '1.0',
            'description'   => '',
            'class_count'   => $class_count,
            'imported_at'   => time(),
        ];
        update_option( self::$option_packs, $packs );

        return $slug;
    }

    public function get_all() {
        return get_option( self::$option_packs, [] );
    }

    public function get_pack( $slug ) {
        $packs = get_option( self::$option_packs, [] );
        return $packs[ $slug ] ?? null;
    }

    public function get_classes_by_pack( $slug ) {
        $all_classes = get_option( self::$option_classes, [] );
        $result = [];
        foreach ( $all_classes as $cls ) {
            if ( isset( $cls['pack_slug'] ) && $cls['pack_slug'] === $slug ) {
                $result[] = $cls;
            }
        }
        return $result;
    }

    private function sanitize_css_value( $css ) {
        $css = wp_strip_all_tags( $css );
        $css = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $css );
        $css = preg_replace( '/expression\s*\(|behavior\s*:|url\s*\(\s*["\']?\s*javascript:/i', '', $css );
        $css = preg_replace( '/@import\s+/i', '', $css );
        $css = preg_replace( '/<\/?style[^>]*>/i', '', $css );
        $css = preg_replace( '/url\s*\(\s*["\']?(?!https?:\/\/|data:)[^"\')]+["\']?\s*\)/i', '', $css );
        return trim( $css );
    }
}
