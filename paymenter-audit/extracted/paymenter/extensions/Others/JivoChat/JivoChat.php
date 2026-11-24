<?php

namespace Paymenter\Extensions\Others\JivoChat;

use App\Classes\Extension\Extension;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\HtmlString;

class JivoChat extends Extension
{
    public function getName(): string
    {
        return 'JivoChat Live Chat';
    }

    public function getDescription(): string
    {
        return 'Integrates JivoChat live chat widget into your site for real-time customer support.';
    }

    public function getVersion(): string
    {
        return '1.1';
    }

    public function getAuthor(): string
    {
        return 'QKingsoftware';
    }

    public function getConfig($values = []): array
    {
        return [
            [
                'name' => 'widget_id',
                'type' => 'text',
                'label' => 'JivoChat Widget ID',
                'description' => 'Your JivoChat Widget ID (found in JivoChat settings, e.g., xW1LkxCxUy).',
                'required' => true,
                'value' => $values['widget_id'] ?? '',
            ],
        ];
    }

    public function boot(): void
    {
        // Only GET requests
        if (!request()->isMethod('GET')) {
            return;
        }

        $widgetId = $this->config('widget_id', '');

        // No widget ID? No widget.
        if (empty($widgetId)) {
            return;
        }

        // Inject into <head>
        Event::listen('head', function () use ($widgetId) {
            $script = '<!-- JivoChat Live Chat Widget -->' . "\n";
            $script .= '<script src="//code.jivosite.com/widget/' . $widgetId . '" async></script>' . "\n";
            $script .= '<!-- End of JivoChat Live Chat -->';
            
            return ['view' => new HtmlString($script)];
        });
    }

    public function enabled(): void {}
    public function disabled(): void {}
    public function updated(): void {}
    public function install(): void {}
}