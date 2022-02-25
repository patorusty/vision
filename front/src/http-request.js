import axios from "axios";

const http = axios.create({
  baseURL: process.env.VUE_APP_ROOT_API,
  withCredentials: true
});
console.log(localStorage.getItem('token'));
http.defaults.headers = {
  'Content-Type': 'application/json',
  'Authorization': `Bearer ${localStorage.getItem('token')}`
}

export default {
  get(url) {
    return http.get(url);
  },

  getOne(url, id) {
    return http.get(`${url}/${id}`);
  },

  post(url, data) {
    return http.post(url, data);
  },

  put(url, id, data) {
    return http.put(`${url}/${id}`, data);
  },
  patch(url, id, data) {
    return http.patch(`${url}/${id}`, data);
  },
  delete(url, id) {
    return http.delete(`${url}/${id}`);
  },
};

