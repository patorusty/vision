import axios from "axios";

const HTTP = axios.create({
  baseURL: `http://127.0.0.1:8000/api/`
});

// const RESOURCE_NAME = 'clientes';

export default {
  load(url) {
    return HTTP.get(url);
  },

  loadOne(url, id) {
    return HTTP.get(`${url}/${id}`);
  },

  create(url, data) {
    return HTTP.post(url, data);
  },

  update(url, id, data) {
    return HTTP.put(`${url}/${id}`, data);
  },
  delete(url, id) {
    return HTTP.delete(`${url}/${id}`);
  },
  search(url, data) {
    return HTTP.post(url, data);
  },
  search2(url, param1, param2) {
    return HTTP.get(`${url}/${param1}/${param2}`);
  }
};
