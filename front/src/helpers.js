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
    dateOrNo(date) {
      if (date) {
        return moment(date).format("DD/MM/YYYY");
      } else {
        return "NO";
      }
    },
    toUpper(nombre, e) {
      if (this[`${nombre}`] != null)
        this[`${nombre}`] = this[`${nombre}`].toUpperCase();
    },
    suma(riesgo) {
      var valor = riesgo.valor_vehiculo === undefined || riesgo.valor_vehiculo === null ? 0 : parseInt(riesgo.valor_vehiculo)
      var gnc = riesgo.valor_gnc === undefined || riesgo.valor_gnc === null ? 0 : parseInt(riesgo.valor_gnc)
      var ac1 = riesgo.valor_accesorio_01 === undefined || riesgo.valor_accesorio_01 === null ? 0 : parseInt(riesgo.valor_accesorio_01)
      var ac2 = riesgo.valor_accesorio_02 === undefined || riesgo.valor_accesorio_02 === null ? 0 : parseInt(riesgo.valor_accesorio_02)
      return riesgo.valor_vehiculo != null
        ? valor+gnc+ac1+ac2
        : "";
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
    modelosFiltrados() {
      return this.modelos.filter(item =>
        this.marca_id != 0
          ? item.automotor_marca_id === this.marca_id
          : item.automotor_marca_id != 0
      );
    },
    versionesFiltradas() {
      return this.versiones.filter(item =>
        this.modelo_id != 0
          ? item.automotor_modelo_id === this.modelo_id
          : item.automotor_modelo_id != 0
      );
    },
    detalle_endosos_por_tipo() {
      return this.endoso.tipo_endoso_id == 1
        ? this.detalle_endosos.filter(e => e.tipo_endoso_id == 1)
        : this.detalle_endosos.filter(e => e.tipo_endoso_id == 2);
    }
  },
};
