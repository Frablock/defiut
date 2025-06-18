import React from "react";
import { useNavigate } from "react-router-dom";
import { Button, Fade, Form, FormGroup, Input, Label } from "reactstrap";
import CustomButton from "../utils/CustomButton";

export default function Register(props) {
   const navigate = useNavigate();
   
   // State for form inputs
   const [formData, setFormData] = React.useState({
       usermail: "",
       username: "",
       password: "",
       confirmPassword: ""
   });
   
   // State for validation errors
   const [errors, setErrors] = React.useState({});
   
   // State for loading
   const [isLoading, setIsLoading] = React.useState(false);
   
   const handleOnClickLogin = () => {
       navigate("/app");
   };

   // Handle input changes
   const handleInputChange = (e) => {
       const { name, value } = e.target;
       setFormData(prev => ({
           ...prev,
           [name]: value
       }));
       
       // Clear error when user starts typing
       if (errors[name]) {
           setErrors(prev => ({
               ...prev,
               [name]: ""
           }));
       }
   };

   // Validate form
   const validateForm = () => {
       const newErrors = {};
       
       // Email validation
       if (!formData.usermail.trim()) {
           newErrors.usermail = "L'email est requis";
       } else if (!/\S+@\S+\.\S+/.test(formData.usermail)) {
           newErrors.usermail = "Format d'email invalide";
       }
       
       // Username validation
       if (!formData.username.trim()) {
           newErrors.username = "Le pseudonyme est requis";
       } else if (formData.username.length < 3) {
           newErrors.username = "Le pseudonyme doit contenir au moins 3 caractères";
       }
       
       // Password validation
       if (!formData.password.trim()) {
           newErrors.password = "Le mot de passe est requis";
       } else if (formData.password.length < 6) {
           newErrors.password = "Le mot de passe doit contenir au moins 6 caractères";
       }
       
       // Confirm password validation
       if (!formData.confirmPassword.trim()) {
           newErrors.confirmPassword = "La confirmation du mot de passe est requise";
       } else if (formData.password !== formData.confirmPassword) {
           newErrors.confirmPassword = "Les mots de passe ne correspondent pas";
       }
       
       return newErrors;
   };

   const handleRegistration = async (e) => {
       e.preventDefault();
       
       // Validate form
       const validationErrors = validateForm();
       if (Object.keys(validationErrors).length > 0) {
           setErrors(validationErrors);
           return;
       }
       
       setIsLoading(true);
       
       try {
           const result = await props.sendData({
               route: "/register", // Fixed route to match backend
               data: {
                   usermail: formData.usermail,
                   username: formData.username,
                   password: formData.password
               },
               method: "POST"
           });
           
           // Handle success
           if (result && !result.error) {
               // Navigate to dashboard
               props.navigateTo("/lobby");
               props.setAuthToken(result.data.token)
               props.setLogedIn(true)
           } else {
               // Handle backend errors
               setErrors({
                   general: result?.error_message || "Erreur lors de l'inscription"
               });
           }
       } catch (error) {
           console.error("Registration error:", error);
           setErrors({
               general: "Erreur de connexion au serveur"
           });
       } finally {
           setIsLoading(false);
       }
   };

   React.useEffect(() => {
        props.setShowLeftNavigation(false);
        props.setShowLeaderboard(false);
    }, []);
   
   return (
    <Fade in={!props.unmount} className="w-100 h-100">
       <div className="d-flex flex-column w-100 h-100 gap-4 align-items-center justify-content-center">
           <h1
            style={{
                    textShadow: "2px 2px 5px rgba(0, 0, 0, 0.36)",
                    color: props.isDarkMode ? "white" : "#4625ba",
                    transition: "color 0.8s",
                    fontWeight: "600",
                }}
            >
            Inscription
           </h1>
           
           <Form onSubmit={handleRegistration} className="d-flex flex-column gap-3 align-items-center" style={{width: "40vw", maxWidth: "300px"}}>
                {/* General error message */}
                {errors.general && (
                    <div className="alert alert-danger w-100 text-center p-2" style={{fontSize: "0.9rem"}}>
                        {errors.general}
                    </div>
                )}
                
                <FormGroup floating className="w-100">
                    <Input
                        id="usermail"
                        name="usermail"
                        placeholder=""
                        type="email"
                        className={`shadow ${errors.usermail ? 'is-invalid' : ''}`}
                        value={formData.usermail}
                        onChange={handleInputChange}
                        disabled={isLoading}
                    />
                    <Label htmlFor="usermail">
                        Email
                    </Label>
                    {errors.usermail && (
                        <div className="invalid-feedback d-block">
                            {errors.usermail}
                        </div>
                    )}
                </FormGroup>
                
                <FormGroup floating className="w-100">
                    <Input
                        id="username"
                        name="username"
                        placeholder=""
                        type="text"
                        className={`shadow ${errors.username ? 'is-invalid' : ''}`}
                        value={formData.username}
                        onChange={handleInputChange}
                        disabled={isLoading}
                    />
                    <Label htmlFor="username">
                        Pseudonyme
                    </Label>
                    {errors.username && (
                        <div className="invalid-feedback d-block">
                            {errors.username}
                        </div>
                    )}
                </FormGroup>
                
                <FormGroup floating className="w-100">
                    <Input
                        id="password"
                        name="password"
                        placeholder=""
                        type="password"
                        className={`shadow ${errors.password ? 'is-invalid' : ''}`}
                        value={formData.password}
                        onChange={handleInputChange}
                        disabled={isLoading}
                    />
                    <Label htmlFor="password">
                        Mot de Passe
                    </Label>
                    {errors.password && (
                        <div className="invalid-feedback d-block">
                            {errors.password}
                        </div>
                    )}
                </FormGroup>
                
                <FormGroup floating className="w-100">
                    <Input
                        id="confirmPassword"
                        name="confirmPassword"
                        placeholder=""
                        type="password"
                        className={`shadow ${errors.confirmPassword ? 'is-invalid' : ''}`}
                        value={formData.confirmPassword}
                        onChange={handleInputChange}
                        disabled={isLoading}
                    />
                    <Label htmlFor="confirmPassword">
                        Confirmation du mot de passe
                    </Label>
                    {errors.confirmPassword && (
                        <div className="invalid-feedback d-block">
                            {errors.confirmPassword}
                        </div>
                    )}
                </FormGroup>
                
                <div className="d-flex flex-column gap-4 mt-3">
                    <CustomButton
                        lightColor={"#4625ba"}
                        darkColor={"#4625ba"}
                        onClick={handleRegistration}
                        disabled={isLoading}
                        type="submit"
                    >
                        <div className="d-flex flex-row align-items-center justify-content-center position-relative">
                            <div style={{fontWeight: "650"}}>
                                {isLoading ? "Création..." : "Créer son compte"}
                            </div>
                            {!isLoading && (
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="bi bi-arrow-right position-absolute" style={{right: "0"}} viewBox="0 0 16 16">
                                    <path fillRule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                </svg>
                            )}
                        </div>
                    </CustomButton>
                    
                    <div className="d-flex flex-row align" style={{color: props.isDarkMode ? "white" : "black", transition: "all 0.8s"}}>
                        Déjà un compte ? &nbsp;
                        <div className="text-decoration-underline" style={{cursor: "pointer"}} onClick={() => props.navigateTo("/login")}>
                            Se connecter
                        </div>
                    </div>
                </div>
            </Form>
       </div>
       </Fade>
   );
}
