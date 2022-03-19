import http from "./http-request";
import axios from "axios";
import moment from "moment";
// import "moment/locale/es"; // without this line it didn't work
// moment.locale("es");
import Cookie from "js-cookie";
import createNumberMask from "text-mask-addons/dist/createNumberMask";
const currencyMask = createNumberMask({
  prefix: "$",
  allowDecimal: false,
  includeThousandsSeparator: true,
  allowNegative: false
});
export const helpers = {
  data: () => ({
    currencyMask,
    rules: {
      required: value => !!value || "Este campo obligatorio"
    }
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
    patenteYcobertura(item) {
      return `${item.patente} - ${item.cobertura.nombre}`;
    },
    nombreCompleto(item) {
      if (item.razon_social == null) {
        return `${item.apellido}  ${item.nombre}`;
      } else {
        return item.razon_social;
      }
    },
    textCompleto(nro) {
      return nro === 1 || nro === true ? "SI" : "NO";
    },
    async cargarLocalidades() {
      const resp = await http.get("/localidades");
      this.localidades = resp.data;
    },
    dateToString(date) {
      if (date) {
        return moment(date, "YYYY-MM-DD").format("DD/MM/YYYY");
      } else {
        return "";
      }
    },
    stringToDate(date) {
      if (date) {
        return moment(date, "DD/MM/YYYY").format("YYYY-MM-DD");
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
    sumarMes() {
      this.poliza.vigencia_hasta = moment(this.poliza.vigencia_desde).add(
        this.poliza.tipo_vigencia_id,
        "M"
      );
    },
    updateVigencia(value) {
      this.poliza.vigencia_desde = moment(value, "DD/MM/YYYY")
        .utc()
        .local();
      this.sumarMes();
    },
    toUpper(nombre, e) {
      if (this[`${nombre}`] != null)
        this[`${nombre}`] = this[`${nombre}`].toUpperCase();
    },
    suma(riesgo) {
      var valor =
        riesgo.valor_vehiculo === undefined || riesgo.valor_vehiculo === null
          ? 0
          : parseInt(riesgo.valor_vehiculo);
      var gnc =
        riesgo.valor_gnc === undefined || riesgo.valor_gnc === null
          ? 0
          : parseInt(riesgo.valor_gnc);
      var ac1 =
        riesgo.valor_accesorio_01 === undefined ||
        riesgo.valor_accesorio_01 === null
          ? 0
          : parseInt(riesgo.valor_accesorio_01);
      var ac2 =
        riesgo.valor_accesorio_02 === undefined ||
        riesgo.valor_accesorio_02 === null
          ? 0
          : parseInt(riesgo.valor_accesorio_02);
      return riesgo.valor_vehiculo != null ? valor + gnc + ac1 + ac2 : "";
    },
    getCookie() {
      axios.defaults.baseURL = process.env.VUE_APP_ROOT_API;
      let token = Cookie.get("XSRF-TOKEN");
      if (token) {
        return new Promise(resolve => {
          resolve(token);
        });
      }
      return axios.get("sanctum/csrf-cookie", {
        withCredentials: true
      });
    },
    envio(item) {
      if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_email === null
      ) {
        return "Recibida";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_email !== null
      ) {
        return "Email";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_email === null
      ) {
        return "Entregada";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_email !== null
      ) {
        return "Entregada / Email";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email !== null
      ) {
        return "Entregada / Email";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original !== null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email === null
      ) {
        return "Entregada";
      } else if (
        item.fecha_recepcion !== null &&
        item.fecha_entrega_original === null &&
        item.fecha_entrega_correo !== null &&
        item.fecha_entrega_email !== null
      ) {
        return "Enviada por Email";
      } else {
        return "No Recibida";
      }
    },
    itemRowBackground(item) {
      switch (item.tipo_reclamo) {
        case "DAÑO A ASEGURADO (Reclamo a Tercero)":
        case "CHOQUE EN CADENA":
          return "orange lighten-2";
        case "DAÑO A ASEGURADO (Cleas)":
        case "DAÑO A ASEGURADO (Cia. vs Cia.)":
        case "DAÑO PARCIAL (Todo Riesgo)":
          return "deep-purple lighten-2";
        case "SIN RECLAMO (Daño a Tercero)":
          return "light-green lighten-2";
        case "CRISTALES LATERALES":
        case "PARABRISAS":
        case "LUNETA":
        case "ROBO PARCIAL":
          return "yellow lighten-2";
        case "ROBO TOTAL":
          return "red lighten-1";
      }
    }
  },
  computed: {
    fechaFormateada: {
      set: function() {
        return this.dateToString(this.cliente.nacimiento);
      },
      get: function() {
        return this.dateToString(this.cliente.nacimiento);
      }
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
  }
};
