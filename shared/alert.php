<style>
    .alertz{
        position:fixed;
        top:20px;
        right:20px;
        pointer-events: none;
        transition:1s;
        transform:translateY(-100px);
        z-index: 9999;
        box-shadow: 0px 0px 2px rgba(0,0,0,0.3);
    }
    .alertz.alertShow{
        transform:translateY(0px);
    }   
</style>
<div id="success" class="alertz alert alert-success">Message: success</div>
<div id="error" class="alertz alert alert-danger">Message: error</div>
<script>
   const alertService = {alert: (data) => {  
        data.response = data.response.toLowerCase().trim();
        data.response = data.response == 'failed' ? 'error' : data.response;
        const el = document.getElementById(data.response);
        el.innerHTML= 'Message: '+ data.message;
        el.classList.add('alertShow');
        window.setTimeout(()=>{
           el.classList.remove('alertShow'); 
        },3000);
    }
}
</script>