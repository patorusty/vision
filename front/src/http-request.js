import axios from "axios";

const getToken = async () => {
  return `Bearer ${localStorage.getItem("visionToken")}`;
};

const http = axios.create({
  baseURL: process.env.VUE_APP_ROOT_API,
  withCredentials: true
});
http.defaults.headers = {
  Accept: "application/json"
};
http.interceptors.request.use(
  async config => {
    const token = await getToken();
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

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
  }
};
