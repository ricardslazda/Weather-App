import axios from "axios";

export default {
    getFreshData() {
        return axios.get("refresh");
    }
}