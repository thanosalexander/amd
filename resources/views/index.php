<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <title>AMD Telecom Assessment</title>
</head>
<body>

<main class="position-relative vh-100">

    <div id="overlay" class="position-absolute flex-column justify-content-center align-items-center w-100 h-100" style="background: rgba(0,0,0,0.3);display: none;">
        <div class="spinner-grow">
            <span class="sr-only">.</span>
        </div>
    </div>

    <section class="py-5 text-center container h-100 d-flex flex-column align-items-center justify-content-center">
        <div class="row w-100">
            <div class="col-12 col-sm-8 mx-auto">
                <h1 class="fw-light">Weather notifier</h1>
                <p>
                    <button id="weather-detector" class="btn btn-primary my-2">
                        Detect weather
                    </button>
                </p>

                <div id="alert" class="alert"></div>
            </div>

        </div>
    </section>


</main>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    function ajax(type,url,data = {}) {
        return new Promise((resolve, reject) => {

            $.ajax({
                url: url,
                type: type,
                dataType: "json",
                data: data,
                success: function (response) {
                    resolve(response)
                },
                error: function (error) {
                    reject(error)
                },
            })
        })
    }

    const Overlay = {
        overlayElement : null,
        init(){
            this.overlayElement = document.querySelector('#overlay')
        },
        show(){
            this.overlayElement.style.display = 'flex'
        },
        hide(){
            this.overlayElement.style.display = 'none'
        }
    }

    const Alert = {
        buttonElement : null,
        alertElement : null,
        init(){
            this.alertElement = document.querySelector('#alert');
        },
        success(message){
            this.clear()
            this.clearStatus();
            this.alertElement.classList.add('alert-success')
            this.setMessage(message)
        },
        error(message){
            this.clear()
            this.clearStatus();
            this.alertElement.classList.add('alert-danger')
            this.setMessage(message)
        },
        append(message){
            if(message) {
                this.alertElement.innerHTML += `<div>${message}</div>`
            }
        },
        clear(){
            this.alertElement.innerHTML = null
        },
        setMessage(message){
            this.alertElement.innerHTML = message
        },
        setStatus(status){

            let statusClass

            switch (status){
                case 'primary':
                    statusClass = 'alert-primary'
                    break;
                case 'secondary':
                    statusClass = 'alert-secondary'
                    break;
                case 'success':
                    statusClass = 'alert-success'
                    break;
                case 'danger':
                    statusClass = 'alert-danger'
                    break;
                case 'warning':
                    statusClass = 'alert-warning'
                    break;
                case 'info':
                    statusClass = 'alert-info'
                    break;
                case 'light':
                    statusClass = 'alert-light'
                    break;
                case 'dark':
                    statusClass = 'alert-dark'
                    break;
                default:
                    break;
            }

            if(statusClass){
                this.clearStatus();
                this.alertElement.classList.add(statusClass)
            }
        },
        removeClassByPrefix(node, prefix) {
            var regx = new RegExp('\\b' + prefix + '[^ ]*[ ]?\\b', 'g');
            node.className = node.className.replace(regx, '');
            return node;
        },
        clearStatus(){
            this.removeClassByPrefix(this.alertElement, 'alert-');
        }
    }

    const SMS = {
        init(){

        },
        resolveTemperature(temperature){

            Alert.append('Sending sms..')

            let message;

            if(temperature > 20){
                message = `My name is Athanasios Alexandris and Temperature is  more than 20C. <${temperature}C>`
            }
            else{
                message = `My name is Athanasios Alexandris and Temperature is less than 20C. <${temperature}C>`
            }

            this.sendSms(message)
        },
        sendSms(message){
            Overlay.show();
            ajax('post','/send-sms',{
                message: message
            })
                .then((response) => {
                    Alert.append(response.message)
                })
                .catch((response) => {
                    Alert.append(response.responseJSON.message)
                    Alert.setStatus('danger')
                })
                .then(()=>{
                    Overlay.hide();
                })
        }
    }

    const Weather = {
        buttonElement : null,

        init(){
            this.buttonElement = document.querySelector('#weather-detector');
            this.registerListeners();
        },
        registerListeners(){
            if(this.buttonElement !== null ){
                this.buttonElement.onclick = (e) =>{

                    e.preventDefault()
                    Alert.clear()
                    Overlay.show();

                    ajax('post','/check-weather')
                        .then((response) => {
                            Overlay.hide();
                            Alert.success(response.message)
                            SMS.resolveTemperature(response.data.temperature)
                        })
                        .catch((response) => {
                            Alert.error(response.responseJSON.message)
                        })
                        .then(()=>{
                            Overlay.hide();
                        })
                }
            }
        },
    }
    document.addEventListener('DOMContentLoaded', function () {
        Overlay.init()
        Alert.init()
        Weather.init()
        SMS.init()
    }, false);
</script>


</body>
</html>