<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Class Namespace
    |--------------------------------------------------------------------------
    |
    | This value sets the root class namespace for Livewire component classes in
    | your application. This value affects component auto-discovery and
    | any Livewire file helper commands, like `artisan make:livewire`.
    |
    | After changing this item, run: `php artisan livewire:discover`.
    |
    */

    'class_namespace' => 'App\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path where Livewire component views are stored.
    | This affects file manipulation helper commands like `artisan make:livewire`.
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    | The default layout view that will be used when rendering a component via
    | Route::get('/some-endpoint', SomeComponent::class);. In this case the
    | the view returned by SomeComponent will be wrapped in "layouts.app"
    |
    */

    'layout' => 'layouts.app',

    /*
    |--------------------------------------------------------------------------
    | Lazy Loading Placeholder
    |--------------------------------------------------------------------------
    | Livewire allows you to lazy load components that would otherwise slow down
    | the initial page load. Every component can have a placeholder which will
    | be displayed while the component is being lazy loaded.
    |
    | For more information see: https://livewire.laravel.com/docs/lazy
    |
    */

    'lazy_placeholder' => null,

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads Endpoint Configuration
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing uploads in a temporary directory
    | before the file is validated and stored permanently. All file uploads
    | are directed to a global endpoint for temporary storage. The config
    | items below are used for customizing various aspects of this process.
    |
    | To learn more, see: https://livewire.laravel.com/docs/uploads
    |
    */

    'temporary_file_upload' => [
        'disk' => null,        // Example: 'local', 's3'              Default: 'default'
        'rules' => null,       // Example: ['file', 'mimes:png,jpg']  Default: ['required', 'file', 'max:12288'] (12MB)
        'directory' => null,   // Example: 'tmp'                      Default  'livewire-tmp'
        'middleware' => null,  // Example: 'throttle:5,1'             Default: 'throttle:60,1'
        'preview_mimes' => [   // Supported file types for temporary pre-signed file URLs.
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5, // Max duration (in minutes) before an upload gets invalidated.
    ],

    /*
    |--------------------------------------------------------------------------
    | Rendering Engine
    |--------------------------------------------------------------------------
    |
    | This value sets the rendering engine that will be used when rendering
    | components. You may leave this empty to use the default rendering
    | engine which is the Blade rendering engine.
    |
    */

    'render_on_redirect' => false,

    /*
    |--------------------------------------------------------------------------
    | Eloquent Model Binding
    |--------------------------------------------------------------------------
    |
    | Previous versions of Livewire supported binding directly to eloquent model
    | properties using wire:model by default. However, this behavior has been
    | deemed too "magical" and has been disabled by default.
    |
    | Setting this value to "true" will enable the old behavior.
    |
    */

    'legacy_model_binding' => false,

    /*
    |--------------------------------------------------------------------------
    | Auto-inject Frontend Assets
    |--------------------------------------------------------------------------
    |
    | By default, Livewire will automatically inject its JavaScript and CSS into the
    | <head> and <body> of pages containing Livewire components. By disabling this,
    | you need to manually include @livewireStyles and @livewireScripts.
    |
    */

    'inject_assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Navigate (SPA mode)
    |--------------------------------------------------------------------------
    |
    | By adding `wire:navigate` to links in your Livewire application, Livewire
    | will prevent the default link handling and instead request those pages
    | via AJAX, creating an SPA-like effect. Configure this behavior here.
    |
    */

    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTML Morph Markers
    |--------------------------------------------------------------------------
    |
    | Livewire utilizes the Livewire Morph algorithm to diff & update content,
    | this allows for selective updates & persistence between re-renders.
    |
    */

    'morph_markers' => [
        // These are used to mark elements that should be persisted between re-renders
        'persist_marker' => 'wire:persist',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination Theme
    |--------------------------------------------------------------------------
    |
    | When paging results, Livewire will use a simple, plain-Jane pagination
    | view. If you'd like to use a different Blade view, you can specify it
    | here. Of course, you can also create your own custom pagination view.
    |
    */

    'pagination_theme' => 'tailwind',

];
