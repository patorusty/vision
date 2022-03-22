import http from "../http-request";
import moment from "moment";

const API_URL = "/polizas";

// const setMediodia = fecha => {
//   var m = moment(fecha, "ddd MMM DD YYYY HH:mm:ss Z+HHmm");
//   m.set("hour", 12);
//   m.set("minute", 0);
//   m.set("second", 0);
//   return m.toJSON();
// };

const state = () => ({
  polizas: [],
  polizas_a_renovar: [],
  polizas_pendientes: [],
  poliza: {
    estado_poliza_id: 100,
    tipo_riesgo_id: 1,
    vigencia_desde: new Date(),
    tipo_vigencia_id: 12,
    fecha_solicitud: new Date(),
    endosos: []
  },
  loading: true,
  tipo_riesgos: [],
  riesgo: {},
  forma_pagos: [],
  tipo_vigencias: [],
  estados: [],
  search: {
    tipo_riesgo_id: 1,
    poliza: "",
    patente: "",
    compania_id: 0,
    cliente: "",
    cliente_id: 0,
    siniestro: "",
    anio: null,
    filtroEstado: [],
    estado: ""
  }
});
const mutations = {
  SET_POLIZAS(state, polizas) {
    state.loading = false;
    state.polizas = polizas;
  },
  SET_POLIZAS_PENDIENTES(state, polizas) {
    state.loading = false;
    state.polizas_pendientes = polizas;
  },
  SET_POLIZAS_A_RENOVAR(state, polizas) {
    state.loading = false;
    state.polizas_a_renovar = polizas;
  },
  SET_POLIZA(state, poliza) {
    state.poliza = poliza;
    state.loading = false;
  },
  RESET_POLIZA(state) {
    state.poliza = Object.assign(
      {},
      {
        estado_poliza_id: 100,
        tipo_riesgo_id: 1,
        vigencia_desde: new Date(),
        tipo_vigencia_id: 12,
        fecha_solicitud: new Date(),
        endosos: [],
        cliente: { nombre: "", apellido: "" }
      }
    );
  },
  CREATE_POLIZA(state, poliza) {
    state.polizas.unshift(poliza);
    state.poliza = poliza;
  },
  UPDATE_POLIZA(state, poliza) {
    if (state.polizas.length) {
      var item = state.polizas.find(item => item.id === poliza.id);
      Object.assign(item, poliza);
    }
  },
  UPDATE_POLIZA_PENDIENTE(state, poliza) {
    var item = state.polizas_pendientes.find(item => item.id === poliza.id);
    Object.assign(item, poliza);
  },
  UPDATE_POLIZA_RENOVADA(state, poliza) {
    var item = state.polizas_a_renovar.find(item => item.id === poliza.id);
    Object.assign(item, poliza);
  },
  DELETE_POLIZA(state, id) {
    state.polizas = state.polizas.filter(c => c.id != id);
  },
  DELETE_POLIZA_PENDIENTE(state, id) {
    state.polizas_pendientes = state.polizas_pendientes.filter(c => c.id != id);
  },
  DELETE_POLIZA_A_RENOVAR(state, id) {
    state.polizas_a_renovar = state.polizas_a_renovar.filter(c => c.id != id);
  },
  SET_TIPO_RIESGOS(state, tipo_riesgos) {
    state.tipo_riesgos = tipo_riesgos;
  },
  SET_FORMA_PAGOS(state, forma_pagos) {
    state.forma_pagos = forma_pagos;
  },
  SET_TIPO_VIGENCIAS(state, tipo_vigencias) {
    state.tipo_vigencias = tipo_vigencias;
  },
  SET_ESTADOS(state, estados) {
    state.estados = estados;
  },
  // SET_ENDOSOS_POR_POLIZA(state, endosos) {
  //   state.poliza.endosos = endosos;
  // },
  SET_RIESGO(state, riesgo) {
    state.riesgo = riesgo;
  },
  SET_SEARCH(state, search) {
    state.search = search;
  },
  CLEAN_SEARCH(state) {
    state.search = Object.assign(
      {},
      {
        tipo_riesgo_id: 1,
        poliza: "",
        patente: "",
        compania_id: 0,
        cliente: "",
        cliente_id: 0,
        siniestro: "",
        anio: null,
        filtroEstado: [],
        estado: ""
      }
    );
  }
};
const actions = {
  async getPolizas({ commit, state }) {
    if (state.polizas.length === 0) {
      const resp = await http.get(API_URL);
      commit("SET_POLIZAS", resp.data);
    }
  },
  async getPolizasPendientes({ commit }) {
    const resp = await http.get("/polizas/pendientes");
    commit("SET_POLIZAS_PENDIENTES", resp.data);
  },
  async getPolizasARenovar({ commit }) {
    const resp = await http.get("/polizas/a_renovar");
    commit("SET_POLIZAS_A_RENOVAR", resp.data);
  },
  async getPoliza({ commit, dispatch, state }, id) {
    if (!state.poliza.id) {
      const resp = await http.getOne(API_URL, id);
      commit("SET_POLIZA", resp.data);
    }
    commit("endoso/SET_ENDOSOS", state.poliza.endosos, { root: true });
    commit("siniestro/SET_SINIESTROS", state.poliza.siniestros, { root: true });
    commit("riesgo/SET_RIESGO_AUTOMOTORES", state.poliza.riesgo_automotor, {
      root: true
    });
    dispatch(
      "codigo_productor/getCodigoProductores",
      state.poliza.compania_id,
      { root: true }
    );
    dispatch("endoso/getTipoEndosos", null, { root: true });
    dispatch("endoso/getDetalleEndosos", null, { root: true });
    dispatch("cobertura/getCoberturasActivas", state.poliza.compania_id, {
      root: true
    });
  },
  async createPoliza({ commit }, poliza) {
    const resp = await http.post(API_URL, poliza);
    if (resp.status === 201) {
      commit("CREATE_POLIZA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Poliza creada con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
  },
  async updatePoliza({ commit }, poliza) {
    const resp = await http.put(API_URL, poliza.id, poliza);
    if (resp.status === 200) {
      commit("UPDATE_POLIZA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Poliza editada con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
  },
  async updatePolizaPendiente({ commit }, poliza) {
    const resp = await http.put(API_URL, poliza.id, poliza);
    if (resp.status === 200) {
      commit("UPDATE_POLIZA_PENDIENTE", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Poliza editada con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
  },
  async renewPoliza({ commit, state }, id) {
    const respStatus = [];
    const resp = await http.getOne(API_URL, id);
    commit("SET_POLIZA", resp.data);
    commit("riesgo/SET_RIESGO_AUTOMOTORES", state.poliza.riesgo_automotor, {
      root: true
    });
    const newPoliza = {
      cliente_id: state.poliza.cliente_id,
      tipo_riesgo_id: state.poliza.tipo_riesgo_id,
      compania_id: state.poliza.compania_id,
      codigo_productor_id: state.poliza.codigo_productor_id,
      renueva_numero: state.poliza.numero,
      tipo_vigencia_id: state.poliza.tipo_vigencia_id,
      vigencia_desde: state.poliza.vigencia_hasta,
      fecha_solicitud: new Date(),
      forma_pago_id: state.poliza.forma_pago_id,
      plan_pago: state.poliza.plan_pago,
      cantidad_cuotas: state.poliza.cantidad_cuotas,
      detalle_medio_pago: state.poliza.detalle_medio_pago,
      comision: state.poliza.comision,
      descuento: state.poliza.descuento,
      estado_poliza_id: 0,
      fecha_emision: null,
      fecha_recepcion: null,
      fecha_entrega_correo: null,
      fecha_entrega_original: null,
      fecha_entrega_mail: null
    };
    const vigencia_hasta = moment(newPoliza.vigencia_desde).add(
      state.poliza.tipo_vigencia_id,
      "M"
    );
    newPoliza.vigencia_hasta = vigencia_hasta;
    const respP = await http.post(API_URL, newPoliza);
    respStatus.push(respP.status);
    state.poliza.riesgo_automotor.forEach(async riesgo => {
      const newRiesgo = { ...riesgo };
      delete newRiesgo.id;
      delete newRiesgo.valor_vehiculo;
      newRiesgo.poliza_id = respP.data.id;
      const respR = await http.post("/riesgo_automotor", newRiesgo);
      respStatus.push(respR.status);
    });
    const oldPoliza = { ...state.poliza };
    const RespNewPolizayRiesgo = await http.getOne(API_URL, respP.data.id);
    commit("CREATE_POLIZA", RespNewPolizayRiesgo.data);
    var finalStatus = false;
    respStatus.forEach(e => {
      e === 201 ? (finalStatus = true) : (finalStatus = false);
    });
    if (finalStatus) {
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Poliza renovada con éxito!"
        },
        { root: true }
      );
      commit("modal/HIDE_MODAL", false, { root: true });
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "red",
          snackText: "Algo salió mal..."
        },
        { root: true }
      );
    }
    oldPoliza.renovada = 1;
    const res = await http.put(API_URL, oldPoliza.id, oldPoliza);
    commit("UPDATE_POLIZA", res.data);
    // await dispatch('checkPolizas');
  },

  async updatePolizaRenovada({ commit, dispatch }, poliza) {
    const resp = await http.put(API_URL, poliza.id, poliza);
    if (resp.status === 200) {
      commit("UPDATE_POLIZA_RENOVADA", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Poliza editada con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
    await dispatch("checkPolizas");
  },

  async deletePoliza({ commit, dispatch }, id) {
    const resp = await http.delete(API_URL, id);
    if (resp.status === 200) {
      commit("DELETE_POLIZA", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          snackbar: true,
          color: "success",
          snackText: "Poliza eliminada con éxito!"
        },
        { root: true }
      );
      commit("modal/HIDE_MODAL", false, { root: true });
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Algo salió mal..."
        },
        { root: true }
      );
    }
    await dispatch("checkPolizas");
  },
  async getTipoRiesgos({ commit, state }) {
    if (state.tipo_riesgos.length === 0) {
      const resp = await http.get("tiporiesgos");
      commit("SET_TIPO_RIESGOS", resp.data);
    }
  },
  async getFormaPagos({ commit, state }) {
    if (state.forma_pagos.length === 0) {
      const resp = await http.get("formapagos");
      commit("SET_FORMA_PAGOS", resp.data);
    }
  },
  async getTipoVigencias({ commit, state }) {
    if (state.tipo_vigencias.length === 0) {
      const resp = await http.get("tipovigencias");
      commit("SET_TIPO_VIGENCIAS", resp.data);
    }
  },
  async getEstados({ commit, state }) {
    if (state.estados.length === 0) {
      const resp = await http.get("estado_polizas");
      commit("SET_ESTADOS", resp.data);
    }
  },
  async checkPolizas() {
    const resp = await http.get("checkpolizas");
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters: {}
};
