import axios from "axios";

const http = axios.create({
  baseURL: `http://127.0.0.1:8000/api/`
});

// const RESOURCE_NAME = 'clientes';

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

