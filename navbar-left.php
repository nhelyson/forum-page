<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="input-search-mobile text-center d-flex flex-column justify-content-center align-items-center" style="display: none!important;">
<button class="btn-close btn-close-white" id="close-input" aria-label="Close">X</button>
<div class="content-input">
 <div class="fs-3">Recherchez des discussions</div>
 <div class="receive-input position-relative" style="left: 5rem;">
     <input type="text" class="form-control bg-warning w-50"  id="input-search" placeholder="Rechercher...">
 </div>
 </div>
</div>
<nav class="navbar navbar-expand-lg" id="menu-left">
                <div class="container">
                    <a class="navbar-brand  fs-1 ms-3" href="#" id="color" style="font-family: 'Lobster'!important;">I see you</a>
                    <button class="btn btn-light btn-circle mx-auto none" id="bouton-input">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                        <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <ul class="nav mx-auto input" style="left:5rem;" id="input-search"><li class="nav-item">
                        <input type="search" style="width: 20vw;" class="form-control bg-input border-0" id="input-search" placeholder="Rechercher..." aria-label="Rechercher"></li>
                    </ul>
                    <div class="d-flex me-auto">
                    <button type="button" class="btn btn-white border text-white me-5 btn-circle btn-none" id="toggle-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="moon" style="display:none" fill="#000000" stroke="#fff" width="20px" height="20px" viewBox="0 0 32 32" version="1.1">
                        <title>moon</title>
                        <path d="M29.223 24.178l-0.021-0.057c-0.116-0.274-0.383-0.463-0.694-0.463-0.104 0-0.202 0.021-0.292 0.059l0.005-0.002c-1.272 0.542-2.752 0.857-4.306 0.857-6.213 0-11.25-5.037-11.25-11.25 0-4.66 2.833-8.658 6.871-10.366l0.074-0.028 0.211-0.089c0.267-0.118 0.45-0.381 0.45-0.687 0-0.375-0.276-0.686-0.635-0.74l-0.004-0.001c-0.653-0.103-1.407-0.161-2.174-0.161-8.145 0-14.748 6.603-14.748 14.748s6.603 14.748 14.748 14.748c4.748 0 8.973-2.244 11.67-5.73l0.025-0.034c0.097-0.125 0.155-0.285 0.155-0.458 0-0.127-0.031-0.246-0.086-0.351l0.002 0.004zM22.518 28.24c-1.497 0.637-3.238 1.007-5.066 1.007-7.317 0-13.249-5.932-13.249-13.249 0-7.074 5.543-12.853 12.523-13.23l0.034-0.001c-3.395 2.326-5.594 6.183-5.594 10.554 0 7.043 5.709 12.752 12.752 12.752 0.85 0 1.681-0.083 2.485-0.242l-0.081 0.013c-1.081 0.976-2.339 1.783-3.716 2.364l-0.087 0.033z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="sun" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="25px" viewBox="0 0 32 32" version="1.1">
                            <g id="icomoon-ignore">
                                <title>sun</title>
                            </g>
                            <path d="M16 8.010c-4.417 0-7.997 3.581-7.997 7.998 0 4.415 3.58 7.996 7.997 7.996s7.997-3.58 7.997-7.996c0-4.416-3.58-7.998-7.997-7.998zM16 22.938c-3.821 0-6.931-3.109-6.931-6.93 0-3.822 3.109-6.932 6.931-6.932s6.931 3.11 6.931 6.932c0 3.821-3.109 6.93-6.931 6.93z" fill="#000000">
                            
                            </path>
                            <path d="M15.471 0.006h1.066v6.405h-1.066v-6.405z" fill="#000000">
                            
                            </path>
                            <path d="M15.471 25.604h1.066v6.39h-1.066v-6.39z" fill="#000000">
                            
                            </path>
                            <path d="M0.006 15.467h6.397v1.066h-6.397v-1.066z" fill="#000000">
                            
                            </path>
                            <path d="M25.596 15.467h6.398v1.066h-6.398v-1.066z" fill="#000000">
                            
                            </path>
                            <path d="M26.936 4.28l0.754 0.754-4.458 4.458-0.754-0.754 4.458-4.458z" fill="#000000">
                            
                            </path>
                            <path d="M5.072 27.653l-0.754-0.754 4.458-4.458 0.754 0.754-4.458 4.458z" fill="#000000">
                            
                            </path>
                            <path d="M5.073 4.281l4.458 4.458-0.754 0.754-4.458-4.458 0.754-0.754z" fill="#000000">
                            
                            </path>
                            <path d="M26.937 27.654l-4.458-4.458 0.754-0.754 4.458 4.458-0.754 0.754z" fill="#000000">
                            
                            </path>
                            </svg>
                    </button>
                    <ul class="nav">
                        <li class="nav-item"><a href="post.php"><button class="btn btn-warning  text-white  ms-auto d-lg-block relive moder discussion" id="ret">+ discussion</button></a></li>
                    </ul>
                     <button class="ms-auto d-lg-none d-md-block open-menu" id="navbar-toggler">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 0 24 24" version="1.1">
                            <title>Menu</title>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Menu">
                                    <rect id="Rectangle" fill-rule="nonzero" x="0" y="0" width="24" height="24">
                        
                        </rect>
                                    <line x1="5" y1="7" x2="19" y2="7" id="Path" stroke="#0C0310" stroke-width="2" stroke-linecap="round">
                        
                        </line>
                                    <line x1="5" y1="17" x2="19" y2="17" id="Path" stroke="#0C0310" stroke-width="2" stroke-linecap="round">
                        
                        </line>
                                    <line x1="5" y1="12" x2="19" y2="12" id="Path" stroke="#0C0310" stroke-width="2" stroke-linecap="round">
                        
                        </line>
                                </g>
                            </g>
                        </svg>
                     </button>
            </nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>