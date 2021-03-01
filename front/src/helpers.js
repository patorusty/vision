import http from "./http-request";
import moment from "moment";


export const helpers = {
  data: () => ({
    rules: {
      required: (value) => !!value || "Este campo obligatorio",
    },
  }),
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
    codigoProdText(item) {
      return `${item.productores.apellido} ${item.productores.nombre} Cod. (${item.codigo_productor})`;
    },
    nombreCompleto(item) {
      return `${item.apellido}  ${item.nombre}`;
    },
    textCompleto(nro) {
      return nro === 1 || nro === true ? "SI" : "NO";
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
