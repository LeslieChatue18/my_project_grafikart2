import { startStimulusApp } from '@symfony/stimulus-bridge';

// Initialize the Stimulus application
const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

// Expose the app for external use
export { app };

// Register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
