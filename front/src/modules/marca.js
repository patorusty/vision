import http from "../http-request";

const API_URL = '/administracion/marcas';

const state = () => ({
    marcas: [],
    marca: {nombreOriginal:''},
    
    loading: false
});
const mutations = {
    SET_MARCAS(state, marcas) {
        state.loading = false
        state.marcas = marcas;
    },
    SET_MARCA(state, marca) {
        state.marca = marca;
        state.marca.nombreOriginal = marca.nombre;
    },
    RESET_MARCA(state) {
        state.marca = Object.assign(
            {},
            {
            }
        );
    },
    UPDATE_MARCA(state, marca) {
        const item = state.marcas.find(item => item.id === marca.id);
        Object.assign(item, marca);
    },
    CREATE_MARCA(state, marca) {
        state.marcas.push(marca);
    },
    DELETE_MARCA(state, id) {
        state.marcas = state.marcas.filter(u => u.id != id);
    }
};
const actions = {
    async getMarcas({ state, commit }) {
        if(state.marcas.length == 0){
            state.loading = true;
        }
        const resp = await http.get(API_URL);
        commit("SET_MARCAS", resp.data);
    },

    async getMarca({ commit }, id) {
        const resp = await http.getOne(API_URL, id);
        commit("SET_MARCA", resp.data);
    },

    async updateMarca({ commit }, marca) {
        const resp = await http.put(
            API_URL,
            marca.id,
            marca
        );
        if (resp.status === 200) {
            commit("UPDATE_MARCA", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Marca editada con éxito!"
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

    async createMarca({ commit }, marca) {
        const resp = await http.post(API_URL, marca);
        if (resp.status === 201) {
            commit("CREATE_MARCA", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Marca creada con éxito!"
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

    async deleteMarca({ commit }, id) {
        const resp = await http.delete(API_URL, id);
        if (resp.status === 200) {
            commit("DELETE_MARCA", id);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Marca eliminada con éxito!"
                },
                { root: true }
            );
        } else if (resp.status === 202) {
            commit(
                "snackbar/SHOW_SNACK", {
                color: "red",
                snackText: "Existen modelos relacionados a este marca"
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
