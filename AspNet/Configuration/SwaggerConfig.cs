using Microsoft.Extensions.DependencyInjection;
using Microsoft.OpenApi.Models;

namespace Aspnet.Configuration
{
    public static class SwaggerConfig
    {
        public static void AddSwaggerConfiguration(this IServiceCollection services)
        {
            services.AddSwaggerGen(c =>
            {
                c.SwaggerDoc("v1", new OpenApiInfo
                {
                    Title = "API Documentation For Projet CLoud Web Service",
                    Version = "v1",
                    Description = "Documentation de l'API de votre application ASP.NET Core"
                });
            });
        }

        public static void UseSwaggerConfiguration(this IApplicationBuilder app)
        {
            app.UseSwagger(); 
            app.UseSwaggerUI(c =>
            {
                c.SwaggerEndpoint("/swagger/v1/swagger.json", "API v1");
                c.RoutePrefix = string.Empty; 
            });
        }
    }
}
