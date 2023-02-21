import { startStimulusApp } from '@symfony/stimulus-bridge';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

import LiveController from '@symfony/ux-live-component';
import '@symfony/ux-live-component/styles/live.css';

app.register('live', LiveController);
