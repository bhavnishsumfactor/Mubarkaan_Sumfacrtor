<style>
    .page-loader {
        display: none; 
    }
    .loading {
        overflow: hidden; 
    }
    .loading .page-loader {
        position: fixed;
        top:0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center; 
    } 
    .loading .page-loader .preloader {
        width: 48px;
        height: 48px;
        border: 3px solid #FFF;
        border-radius: 50%;
        display: inline-block;
        position: relative;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }
    .loading .page-loader .preloader::after {
        content: '';  
        box-sizing: border-box;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-bottom-color: var(--brand-color);
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    } 
</style>
<div class="page-loader YK-loader">
    <div class="preloader">        
    </div>
</div>
