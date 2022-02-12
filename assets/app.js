import './styles/app.css';
import { createApp } from 'vue'
import App from "./components/App";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";


createApp(App).use(Toast, {transition: "Vue-Toastification__fade"}).mount('#app');
