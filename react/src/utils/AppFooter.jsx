export default function AppFooter(props) {

 return (
  <>
    <div
      className="d-flex flex-column w-100 shadow justify-content-center align-items-center"
      style={{
        backgroundColor: props.isDarkMode ? "#535353" : "#e2ddf7",
        transition: "background-color 0.8s ease, opacity 0.8s",
        height:"75px"
      }}
      ref={props.footerRef}
    >
      <div className="text-decoration-underline transition" style={{cursor:"pointer", color: props.isDarkMode ? "white" : "black"}} onClick={() => props.navigateTo("/legal")}>
          Mentions légales
      </div>
      <div className="text-decoration-underline transition" style={{cursor:"pointer", color: props.isDarkMode ? "white" : "black"}} onClick={() => props.navigateTo("/login")}>
          Work in progress ...
      </div>
    </div>
  </>
 );
}