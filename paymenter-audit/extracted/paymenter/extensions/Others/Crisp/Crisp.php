<?php

namespace Paymenter\Extensions\Others\Crisp;

use App\Classes\Extension\Extension;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\HtmlString;

class Crisp extends Extension
{
    public function getName(): string
    {
        return 'Crisp Live Chat';
    }

    public function getDescription(): string
    {
        return 'Integrates Crisp live chat widget into your site for real-time customer support.';
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
                'name' => 'website_id',
                'type' => 'text',
                'label' => 'Crisp Website ID',
                'description' => 'Your Crisp Website ID (can be found in Crisp dashboard → Settings → Website Settings).',
                'required' => true,
                'value' => $values['website_id'] ?? '',
            ],
            [
                'name' => 'position',
                'type' => 'select',
                'label' => 'Chat Position',
                'description' => 'Where to display the Crisp chat widget.',
                'required' => true,
                'options' => [
                    'bottom-right' => 'Bottom Right',
                    'bottom-left' => 'Bottom Left',
                ],
                'value' => $values['position'] ?? 'bottom-right',
            ],
        ];
    }

    public function boot(): void
    {
        // Only GET
        if (!request()->isMethod('GET')) {
            return;
        }

        $websiteId = $this->config('website_id');
        $position = $this->config('position', 'bottom-right');

        // No website ID? No widget.
        if (empty($websiteId)) {
            return;
        }

        $headScript = $this->getCrispHeadScript($websiteId, $position);

        // Inject into <head>
        Event::listen('head', function () use ($headScript) {
            return ['view' => new HtmlString($headScript)];
        });
    }

    private function getCrispHeadScript(string $websiteId, string $position): string
    {
        return <<<HTML
<!-- Crisp Live Chat Widget -->
<script type="text/javascript">
window.\$crisp = [];
window.CRISP_WEBSITE_ID = "{$websiteId}";
(function(){
    var d = document;
    var s = d.createElement("script");
    s.src = "https://client.crisp.chat/l.js";
    s.async = 1;
    d.getElementsByTagName("head")[0].appendChild(s);
})();

// Position configuration
(function() {
    function setPosition() {
        if (typeof \$crisp !== "undefined") {
            if ("{$position}" === "bottom-left") {
                \$crisp.push(["config", "position:reverse", true]);
            } else {
                \$crisp.push(["config", "position:reverse", false]);
            }
        }
    }
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", setPosition);
    } else {
        setPosition();
    }
})();
</script>
<!-- End of Crisp Live Chat -->
HTML;
    }

    public function enabled(): void {}
    public function disabled(): void {}
    public function updated(): void {}
    public function install(): void {}
}