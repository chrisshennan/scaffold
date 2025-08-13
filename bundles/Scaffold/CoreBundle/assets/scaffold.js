import ScaffoldMarkdownTextareaController from './controllers/scaffold_markdown_textarea_controller.js';
import 'preline';

// Export a function that accepts the Stimulus application instance
export function registerScaffoldControllers(application) {
    // Manually register the controller
    application.register('scaffold--markdown-textarea', ScaffoldMarkdownTextareaController);
}