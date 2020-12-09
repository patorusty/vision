import http from "./http-request";
import moment from "moment";


export const helpers = {
  methods: {
    localidadesText(item) {
      return `${item.nombre} - ${item.codigo_postal}`;
    },
    textoActivo(nro) {
      return nro === 1 || nro === true ? "Activo" : "Inactivo";
    },
    codigoText(item) {
      return `${item.apellido}  ${item.nombre} (Matricula: ${item.matricula})`;
    },
    codigoOrgText(item) {
      return `${item.organizadores.apellido}  ${item.organizadores.nombre} (C.Org:  ${item.codigo_organizador})`;
    },
    nombreCompleto(item) {
      return `${item.apellido}  ${item.nombre}`;
    },
    async cargarLocalidades() {
      const resp = await http.get("/localidades");
      this.localidades = resp.data;
    },
    formatDate(date) {
      if (date) {
        return moment(date).format("DD/MM/YYYY");
      } else {
        return "";
      }
    },
  },
  computed: {
    fechaFormateada: {
      set: function () {
        return this.formatDate(this.cliente.nacimiento);
      },
      get: function () {
        return this.formatDate(this.cliente.nacimiento);
      },
    },
  },
};
