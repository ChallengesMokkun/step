import { createApp } from 'vue';
import App from './src/App.vue';
import router from './src/router';
import 'viewport-extra';

createApp({
  name: 'STEP',
  components: {
    'step-app': App
  }
})
.use(router)
.mount('#js-app');
