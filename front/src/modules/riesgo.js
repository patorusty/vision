import http from "../http-request";

const API_URL_RA = "/riesgo_automotor";
const API_URL_OR = "/otro_riesgo";

const state = () => ({
  riesgo_automotor: {
    tipo_automotor_id: 1,
    equipo_rastreo: "No",
    uso: "Particular",
    combustible: "Nafta",
    estado_general: "Muy Bueno",
    ajuste: 0,
    valor_vehiculo: null,
    valor_gnc: null,
    valor_accesorio_01: null,
    valor_accesorio_02: null
  },
  otro_riesgo: {},
  riesgo_automotores: [],
  loading: false,
  tipo_vehiculos: [],
  tipo_patentes: [
    {
      value: 0,
      text: "Nacional"
    },
    {
      value: 1,
      text: "Mercosur"
    }
  ],
  combustibles: [
    {
      value: "Nafta",
      text: "Nafta"
    },
    {
      value: "Diesel",
      text: "Diesel"
    },
    {
      value: "GNC",
      text: "GNC"
    }
  ],
  estados: [
    {
      value: "Muy Bueno",
      text: "Muy Bueno"
    },
    {
      value: "Bueno",
      text: "Bueno"
    },
    {
      value: "Regular",
      text: "Regular"
    }
  ],
  usos: [
    {
      value: "Particular",
      text: "Particular"
    },
    {
      value: "Comercial",
      text: "Comercial"
    },
    {
      value: "Particular / Comercial",
      text: "Particular / Comercial"
    },
    {
      value: "Remise",
      text: "Remise"
    },
    {
      value: "Cabify / Uber",
      text: "Cabify / Uber"
    }
  ],
  tipo_carrocerias: [],
  ajustes: [
    {
      value: 0,
      text: "0%"
    },
    {
      value: 10,
      text: "10%"
    },
    {
      value: 20,
      text: "20%"
    },
    {
      value: 30,
      text: "30%"
    }
  ],
  equipos_rastreo: [
    {
      value: "No",
      text: "No"
    },
    {
      value: "Lo Jack",
      text: "Lo Jack"
    },
    {
      value: "Ituran",
      text: "Ituran"
    },
    {
      value: "Otro",
      text: "Otro"
    }
  ],
  tipos_or: [
    {
      value: "Bicicleta",
      text: "Bicicleta"
    },
    {
      value: "Monopatin Electrico",
      text: "Monopatin Electrico"
    },
    {
      value: "Bicicleta Electrica",
      text: "Bicicleta Electrica"
    }
  ]
});

const mutations = {
  SET_RIESGO_AUTOMOTORES(state, riesgo_automotores) {
    state.loading = false;
    state.riesgo_automotores = riesgo_automotores;
  },
  SET_RIESGO_AUTOMOTOR(state, riesgo_automotor) {
    state.riesgo_automotor = riesgo_automotor;
  },
  SET_OTRO_RIESGO(state, otro_riesgo) {
    state.otro_riesgo = otro_riesgo;
  },
  RESET_RIESGO_AUTOMOTOR(state) {
    state.riesgo_automotor = Object.assign(
      {},
      {
        tipo_automotor_id: 1,
        equipo_rastreo: "No",
        uso: "Particular",
        combustible: "Nafta",
        estado_general: "Muy Bueno",
        ajuste: 0,
        valor_vehiculo: null,
        valor_gnc: null,
        valor_accesorio_01: null,
        valor_accesorio_02: null
      }
    );
  },
  RESET_RIESGOS_AUTOMOTORES(state) {
    state.riesgo_automotores = [];
  },
  UPDATE_RIESGO_AUTOMOTOR(state, riesgo_automotor) {
    const item = state.riesgo_automotores.find(
      item => item.id === riesgo_automotor.id
    );
    Object.assign(item, riesgo_automotor);
  },
  UPDATE_KV(state, pair) {
    state.riesgo_automotor = { ...state.riesgo_automotor, ...pair };
  },
  CREATE_RIESGO_AUTOMOTOR(state, riesgo_automotor) {
    state.riesgo_automotores.unshift(riesgo_automotor);
  },
  CREATE_OTRO_RIESGO(state, otro_riesgo) {
    state.otro_riesgo = otro_riesgo;
  },
  UPDATE_OTRO_RIESGO(state, otro_riesgo) {
    state.otro_riesgo = otro_riesgo;
  },
  RESET_OTRO_RIESGO(state) {
    state.otro_riesgo = {};
  },
  DELETE_RIESGO_AUTOMOTOR(state, id) {
    state.riesgo_automotores = state.riesgo_automotores.filter(u => u.id != id);
  },
  SET_TIPO_VEHICULOS(state, tipo_vehiculos) {
    state.tipo_vehiculos = tipo_vehiculos;
  },
  SET_TIPO_CARROCERIAS(state, tipo_carrocerias) {
    state.tipo_carrocerias = tipo_carrocerias;
  }
};
const actions = {
  async getRiesgoAutomotores({ commit }) {
    const resp = await http.get(API_URL_RA);
    commit("SET_RIESGO_AUTOMOTORES", resp.data);
  },

  async getRiesgoAutomotor({ commit, dispatch }, id) {
    const resp = await http.getOne(API_URL_RA, id);
    commit("SET_RIESGO_AUTOMOTOR", resp.data);
    dispatch("modelo/getModelosPorMarca", resp.data.automotor_marca_id, {
      root: true
    });
    commit("marca/SET_MARCA", resp.data.marca, {
      root: true
    });
    dispatch("version/getVersionesPorModelo", resp.data.automotor_modelo_id, {
      root: true
    });
    commit("modelo/SET_MODELO", resp.data.modelo, {
      root: true
    });
  },

  async updateRiesgoAutomotor({ commit }, riesgo_automotor) {
    const resp = await http.put(
      API_URL_RA,
      riesgo_automotor.id,
      riesgo_automotor
    );
    if (resp.status === 200) {
      commit("UPDATE_RIESGO_AUTOMOTOR", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Riesgo editado con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK"({
          color: "success",
          snackText: "Algo salió mal..."
        }),
        { root: true }
      );
    }
  },

  async createRiesgoAutomotor({ commit }, riesgo_automotor) {
    const resp = await http.post(API_URL_RA, riesgo_automotor);
    if (resp.status === 201) {
      commit("CREATE_RIESGO_AUTOMOTOR", resp.data);
      const respUpdatePoliza = await http.getOne(
        "/polizas",
        resp.data.poliza_id
      );
      commit("poliza/UPDATE_POLIZA", respUpdatePoliza.data, { root: true });
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Riesgo creado con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
  },

  async createOtroRiesgo({ commit }, otro_riesgo) {
    const resp = await http.post(API_URL_OR, otro_riesgo);
    if (resp.status === 201) {
      commit("CREATE_OTRO_RIESGO", resp.data);
      const respUpdatedPoliza = await http.getOne(
        "/polizas",
        resp.data.poliza_id
      );
      commit("poliza/UPDATE_POLIZA", respUpdatedPoliza.data, { root: true });
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Riesgo creado con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
  },

  async updateOtroRiesgo({ commit }, otro_riesgo) {
    const resp = await http.put(API_URL_OR, otro_riesgo.id, otro_riesgo);
    if (resp.status === 200) {
      commit("UPDATE_OTRO_RIESGO", resp.data);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Riesgo creado con éxito!"
        },
        { root: true }
      );
      return true;
    } else {
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "red",
          snackText: "Algo salió mal, intente nuevamente..."
        },
        { root: true }
      );
    }
  },

  async deleteRiesgoAutomotor({ commit }, id) {
    const resp = await http.delete(API_URL_RA, id);
    if (resp.status === 200) {
      commit("DELETE_RIESGO_AUTOMOTOR", id);
      commit(
        "snackbar/SHOW_SNACK",
        {
          color: "success",
          snackText: "Riesgo eliminado con éxito!"
        },
        { root: true }
      );
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
  },
  async getTipoVehiculos({ commit }) {
    const resp = await http.get("/tipo_vehiculos");
    commit("SET_TIPO_VEHICULOS", resp.data);
  },
  async getTipoCarrocerias({ commit }) {
    const resp = await http.get("/tipo_carrocerias");
    commit("SET_TIPO_CARROCERIAS", resp.data);
  }
};
export default {
  namespaced: true,
  state,
  mutations,
  actions
};
