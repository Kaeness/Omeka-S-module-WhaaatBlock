<?php
namespace WhaaatBlock\Site\BlockLayout;

use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\View\Renderer\PhpRenderer;

class Faq extends AbstractBlockLayout
{
    public function getLabel()
    {
        return 'FAQ (titre + questions/réponses)'; // @translate
    }

    public function form(PhpRenderer $view, SiteRepresentation $site, SitePageRepresentation $page = null, SitePageBlockRepresentation $block = null )
    {
        $translate = $view->plugin('translate');

        if ( isset($block) && $block ) {

            $title = $block->dataValue('title', '');
            $description = $block->dataValue('description', '');
            $faqs = $block->dataValue('faqs', []);

        } else {

            $title = '';
            $description = '';
            $faqs = [];

        }

        $tempBlockId = (bin2hex(random_bytes(3)));

        ob_start();
        ?>
        <div class="faq-block-form" id="faq<?= $tempBlockId ?>">
            <input type="hidden" id="indexBlock<?= $tempBlockId ?>" name="o:block[__blockIndex__][o:data][index_block]" value="">
            <div class="field">
                <div class="field-meta"><label for="o:block[__blockIndex__][o:data][title]"><?= $translate('FAQ Title') ?></label></div>
                <div class="inputs"><input type="text" name="o:block[__blockIndex__][o:data][title]" id="o:block[__blockIndex__][o:data][title]" value="<?= $view->escapeHtml($title) ?>" class="full-width"></div>
            </div>
            <div class="field">
                <div class="field-meta"><label for="o:block[__blockIndex__][o:data][description]"><?= $translate('FAQ Introduction') ?></label></div>
                <div class="inputs"><textarea name="o:block[__blockIndex__][o:data][description]" id="o:block[__blockIndex__][o:data][description]" rows="5" class="full-width wysiwyg"><?= $description ?></textarea></div>
            </div>

            <h4><?= $translate('Questions/Answers') ?></h4>
            <div class="faq-items">
                <?php if (!empty($faqs)): ?>
                    <?php $i = 0; ?>
                    <?php foreach ($faqs as $item): ?>
                        <?php if (!empty($item['question']) && !empty($item['answer']) ): ?>
                        <div class="faq-item field">
                            <div class="field-meta">
                                <label for="o:block[__blockIndex__][o:data][faqs][<?= $i ?>][question]"><?= $translate('Question').' '.($i+1) ?></label>
                            </div>
                            <div class="inputs">
                                <input type="text" id="o:block[__blockIndex__][o:data][faqs][<?= $i ?>][question]" name="o:block[__blockIndex__][o:data][faqs][<?= $i ?>][question]" value="<?= $view->escapeHtml($item['question']) ?>" class="full-width">
                            </div>
                            <div class="field-meta">
                                <label for="o:block[__blockIndex__][o:data][faqs][<?= $i ?>][answer]"><?= $translate('Answer').' '.($i+1) ?></label>
                            </div>
                            <div class="inputs">
                                <textarea id="o:block[__blockIndex__][o:data][faqs][<?= $i ?>][answer]" name="o:block[__blockIndex__][o:data][faqs][<?= $i ?>][answer]" rows="5" class="full-width wysiwyg"><?= $view->escapeHtml($item['answer']) ?></textarea>
                            </div>
                        </div>
                        <?php $i++; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="button" class="add-faq-button" onclick="faqAddItem<?= $tempBlockId ?>(this)"><?= $translate('+ Add a question') ?></button>

            <div style="margin-top:1em;">
                <button type="button" onclick="faqExportJson<?= $tempBlockId ?>(this)"><?= $translate('Export JSON') ?></button>
                <button type="button" onclick="faqImportJson<?= $tempBlockId ?>(this)"><?= $translate('Import JSON') ?></button>
                <input type="file" accept="application/json" style="display:none;">
            </div>
        </div>

        <script>
        function faqAddItem<?= $tempBlockId ?>(button) {

            const inputElement = document.getElementById('indexBlock<?= $tempBlockId ?>');
            const nameAttribute = inputElement.getAttribute('name');
            const match = nameAttribute.match(/o:block\[(\d+)\]\[o:data\]\[index_block\]/);
            const blockIndex = match ? match[1] : null;

            const container = button.closest('.faq-block-form#faq<?= $tempBlockId ?>').querySelector('#faq<?= $tempBlockId ?> .faq-items');
            const index = container.querySelectorAll('.faq-item').length;
            const html = `<div class="faq-item field">
                <div class="field-meta">
                    <label for="o:block[${blockIndex}][o:data][faqs][${index}][question]"><?= $translate('Question') ?> ${index+1}</label>
                </div>
                <div class="inputs">
                    <input type="text" id="o:block[${blockIndex}][o:data][faqs][${index}][question]" name="o:block[${blockIndex}][o:data][faqs][${index}][question]" class="full-width">
                </div>
                <div class="field-meta">
                    <label for="o:block[${blockIndex}][o:data][faqs][${index}][answer]"><?= $translate('Answer') ?> ${index+1}</label>
                </div>
                <div class="inputs">
                    <textarea id="o:block[${blockIndex}][o:data][faqs][${index}][answer]" name="o:block[${blockIndex}][o:data][faqs][${index}][answer]" rows="5" class="full-width wysiwyg"></textarea>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        function faqExportJson<?= $tempBlockId ?>(button) {
            const form = button.closest('.faq-block-form#faq<?= $tempBlockId ?>');
            const title = form.querySelector('input[name*="[title]"]').value;
            const description = form.querySelector('textarea[name*="[description]"]').value;
            const faqs = [];
            form.querySelectorAll('.faq-item').forEach(item => {
                const question = item.querySelector('input[type="text"]').value;
                const answer = item.querySelector('textarea').value;
                if (question.trim()) faqs.push({question, answer});
            });
            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify({title, description, faqs}, null, 2));
            const dlAnchor = document.createElement('a');
            dlAnchor.setAttribute("href", dataStr);
            dlAnchor.setAttribute("download", "faq_<?= $tempBlockId ?>.json");
            dlAnchor.click();
        }

        function faqImportJson<?= $tempBlockId ?>(button) {
            const form = button.closest('.faq-block-form#faq<?= $tempBlockId ?>');
            const fileInput = button.nextElementSibling;
            fileInput.click();
            fileInput.onchange = () => {
                const file = fileInput.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    try {
                        const data = JSON.parse(e.target.result);
                        if (data.title) form.querySelector('input[name*="[title]"]').value = data.title;
                        if (data.description) form.querySelector('textarea[name*="[description]"]').value = data.description;
                        const container = form.querySelector('#faq<?= $tempBlockId ?> .faq-items');
                        container.innerHTML = '';
                        if (Array.isArray(data.faqs)) {
                            data.faqs.forEach((item, i) => {

                                const inputElement = document.getElementById('indexBlock<?= $tempBlockId ?>');
                                const nameAttribute = inputElement.getAttribute('name');
                                const match = nameAttribute.match(/o:block\[(\d+)\]\[o:data\]\[index_block\]/);
                                const blockIndex = match ? match[1] : null;

                                const html = `<div class="faq-item field">
                                    <div class="field-meta">
                                        <label for="o:block[${blockIndex}][o:data][faqs][${i}][question]"><?= $translate('Question') ?> ${i+1}</label>
                                    </div>
                                    <div class="inputs">
                                        <input type="text" id="o:block[${blockIndex}][o:data][faqs][${i}][question]" name="o:block[${blockIndex}][o:data][faqs][${i}][question]" class="full-width" value="${item.question}">
                                    </div>
                                    <div class="field-meta">
                                        <label for="o:block[${blockIndex}][o:data][faqs][${i}][answer]"><?= $translate('Answer') ?> ${i+1}</label>
                                    </div>
                                    <div class="inputs">
                                        <textarea id="o:block[${blockIndex}][o:data][faqs][${i}][answer]" name="o:block[${blockIndex}][o:data][faqs][${i}][answer]" rows="5" class="full-width wysiwyg">${item.answer}</textarea>
                                    </div>
                                </div>`;
                                container.insertAdjacentHTML('beforeend', html);
                            });
                        }
                    } catch (err) {
                        alert('<?= $translate('Invalid JSON') ?>');
                    }
                };
                reader.readAsText(file);
            };
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {

        $title = $block->dataValue('title', '');
        $description = $block->dataValue('description', '');
        $faqs = $block->dataValue('faqs', []);

        return $view->partial('common/block-layout/faq', [
            'title' => $title,
            'description' => $description,
            'faqs' => $faqs,
        ]);
    }
}
