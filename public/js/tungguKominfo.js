const urllogin = "https://kkpm.banyumaskab.go.id/administrator/auth/login";

const formlogin = new FormData();
formlogin.append("admin_username", "3301010509940003");
formlogin.append("admin_password", "banyumas");

function login() {
    return fetch(urllogin, {
        body: JSON.stringify(formlogin),
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
    }).then((res) => (res.status !== 301 ? null : res.headers));
}

const data = await login();
console.log("🚀 ~ data:", data);
