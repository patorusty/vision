import http from "../http-request";

const API_URL = '/configuracion/productores';

const state = () => ({
    productores: [],
    productor: {
        cuit: "",
        matricula: "",
        activo: true,
        matriuclaOriginal: '',
        cuitOriginal: ''
    }
});
const mutations = {
    SET_PRODUCTORES(state, productores) {
        state.productores = productores;
    },
    SET_PRODUCTOR(state, productor) {
        state.productor = productor;
        state.productor.cuitOriginal = productor.cuit;
        state.productor.matriculaOriginal = productor.matricula;
    },
    RESET_PRODUCTOR(state) {
        state.productor = Object.assign(
            {},
            {
                cuit: "",
                matricula: "",
                activo: true,
                matriuclaOriginal: '',
                cuitOriginal: ''
            }
        );
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
        const resp = await http.load(API_URL);
        commit("SET_PRODUCTORES", resp.data);
    },

    async getProductor({ commit }, id) {
        const resp = await http.loadOne(API_URL, id);
        commit("SET_PRODUCTOR", resp.data);
    },

    async updateProductor({ commit }, productor) {
        const resp = await http.update(
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
        const resp = await http.create(API_URL, productor);
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
        } else {
            commit(
                "snackbar/SHOW_SNACK"({
                    color: "success",
                    snackText: "Algo salió mal..."
                }),
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
