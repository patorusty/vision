import http from "../http-request";

const API_URL = '/configuracion/productores';

const state = () => ({
    riesgo_automotor:{},
    otro_riesgo:{},
    loading: false
});
const mutations = {
    SET_PRODUCTORES(state, productores) {
        state.loading = false
        state.productores = productores;
    },
    SET_RIESGO_AUTOMOTOR(state, riesgo_automotor) {
        state.riesgo_automotor = riesgo_automotor;
    },
    RESET_RIESGO_AUTOMOTOR(state) {
        state.riesgo_automotor = Object.assign({},{});
    },
    UPDATE_PRODUCTOR(state, productor) {
        const item = state.productores.find(item => item.id === productor.id);
        Object.assign(item, productor);
    },
    CREATE_PRODUCTOR(state, productor) {
        state.productores.push(productor);
    },
    DELETE_PRODUCTOR(state, id) {
        state.productores = state.productores.filter(u => u.id != id);
    }
};
const actions = {
    async getProductores({ commit }) {
        const resp = await http.get(API_URL);
        commit("SET_PRODUCTORES", resp.data);
    },

    async getRiesgoAutomotor({ commit }) {
        // const resp = await http.getOne(API_URL, id);
        // commit("SET_RIESGO_AUTOMOTOR", resp.data);
    },

    async updateProductor({ commit }, productor) {
        const resp = await http.put(
            API_URL,
            productor.id,
            productor
        );
        if (resp.status === 200) {
            commit("UPDATE_PRODUCTOR", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Productor editado con éxito!"
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

    async createProductor({ commit }, productor) {
        const resp = await http.post(API_URL, productor);
        if (resp.status === 201) {
            commit("CREATE_PRODUCTOR", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Productor creado con éxito!"
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

    async deleteProductor({ commit }, id) {
        const resp = await http.delete(API_URL, id);
        if (resp.status === 200) {
            commit("DELETE_PRODUCTOR", id);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Productor eliminado con éxito!"
                },
                { root: true }
            );
        } else if (resp.status === 202) {
            commit(
                "snackbar/SHOW_SNACK", {
                color: "red",
                snackText: "Existen Códigos Productores relacionados a este Productor"
            },
                { root: true }
            );
        } else {
            commit(
                "snackbar/SHOW_SNACK", {
                color: "success",
                snackText: "Algo salió mal..."
            },
                { root: true }
            );
        }
    }
};
export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters: {}
};
