import { startStimulusApp } from '@symfony/stimulus-bundle';
const app = startStimulusApp();
import { registerScaffoldControllers } from '@scaffold-core/scaffold.js';
registerScaffoldControllers(app);