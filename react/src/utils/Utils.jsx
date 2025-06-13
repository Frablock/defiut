export const sendData = async ({route = "/", data = {}, method="GET"}) => {
    let options = {method: method}
    if(method == "POST"){
        options.headers = {
            "Content-Type": "application/json",
        }
        options.body = JSON.stringify(data)
    }
    return fetch("/api"+route,options).then((data) => {return data.json()});
}