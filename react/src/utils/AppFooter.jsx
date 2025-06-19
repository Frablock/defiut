export default function AppFooter(props) {
  return (
    <>
      <div
        className="d-flex flex-row w-100 shadow justify-content-center align-items-center gap-4"
        style={{
          backgroundColor: props.isDarkMode ? "#535353" : "#e2ddf7",
          transition: "background-color 0.8s ease, opacity 0.8s",
          height: "75px"
        }}
        ref={props.footerRef}
      >
        <div 
          className="text-decoration-underline transition" 
          style={{
            cursor: "pointer", 
            color: props.isDarkMode ? "white" : "black",
            fontSize: "14px",
            fontWeight: "500"
          }} 
          onClick={() => props.navigateTo("/legal-notices")}
        >
          Mentions légales
        </div>
        
        <div 
          className="text-decoration-underline transition" 
          style={{
            cursor: "pointer", 
            color: props.isDarkMode ? "white" : "black",
            fontSize: "14px",
            fontWeight: "500"
          }} 
          onClick={() => props.navigateTo("/faq")}
        >
          FAQ
        </div>
        
        <div 
          className="text-decoration-underline transition" 
          style={{
            cursor: "pointer", 
            color: props.isDarkMode ? "white" : "black",
            fontSize: "14px",
            fontWeight: "500"
          }} 
          onClick={() => props.navigateTo("/cgu")}
        >
          CGU
        </div>
        <div style={{color:"white"}}>
          © Def'IUT — Tous droits réservés. 2025-2026
        </div>
      </div>
    </>
  );
}
