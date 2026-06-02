import "./bootstrap";

console.log("APP JS MASUK");
console.log(window.Echo);

window.Echo.join("online-users")

    .here((users) => {
        console.log("ONLINE", users);

        users.forEach((user) => {
            let status = document.getElementById(`status-${user.id}`);

            if (status) {
                status.innerHTML = "🟢";
            }
        });
    })

    .joining((user) => {
        console.log("JOINING", user);

        let status = document.getElementById(`status-${user.id}`);

        if (status) {
            status.innerHTML = "🟢";
        }
    })

    .leaving((user) => {
        console.log("LEAVING", user);

        let status = document.getElementById(`status-${user.id}`);

        if (status) {
            status.innerHTML = "🔴";
        }
    });
