<?php

namespace Favon\Application\Abstracts;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\Factory;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

abstract class MjmlMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Build the view by first compiling the blade template, then the MJML template.
     *
     * @return array|string
     */
    public function buildView()
    {
        $html = app(Factory::class)->make($this->view, $this->viewData)->render();
        $compiled = $this->mjmlToHtml($html);

        return array_filter([
            'html' => new HtmlString($compiled),
            'text' => $this->textView ?? null,
        ]);
    }

    /**
     * Compile the MJML template.
     *
     * @param string $html
     * @return null|string
     */
    protected function mjmlToHtml(string $html): ?string
    {
        Log::info('Compiling MJML');
        try {
            $process = new Process('./node_modules/.bin/mjml --stdin --stdout');
            $process->setInput($html);
            $process->mustRun();
            return $process->getOutput();
        } catch (\Exception $e) {
            Log::error('Failed to compile MJML: '.$e->getMessage());
        }

        return null;
    }
}
