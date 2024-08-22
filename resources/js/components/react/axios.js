import axios from 'axios';

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

export default axios;