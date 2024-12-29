using Microsoft.EntityFrameworkCore;
using Npgsql.EntityFrameworkCore.PostgreSQL;
using Aspnet.Models;
using Aspnet.Services;
using Aspnet.Configuration;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddControllers();

// Add services to the container.
builder.Services.AddRazorPages();

builder.Services.AddDbContext<ApplicationDbContext>(options =>
    options.UseNpgsql(builder.Configuration.GetConnectionString("PostgresConnection")));
AppContext.SetSwitch("Npgsql.EnableLegacyTimestampBehavior", true);


builder.Services.AddScoped<AdminService>();
builder.Services.AddScoped<EmailPinsService>();
builder.Services.AddScoped<PinCodeService>();
builder.Services.AddScoped<UsersService>();
builder.Services.AddScoped<TokenService>();
builder.Services.AddScoped<SessionConfigService>();
builder.Services.AddScoped<UtilService>();
builder.Services.AddScoped<SessionUserService>();
builder.Services.AddSwaggerConfiguration();
builder.WebHost.ConfigureKestrel(options =>
{
    options.ConfigureEndpointDefaults(listenOptions =>
    {
        listenOptions.UseConnectionLogging();
    });
});
var app = builder.Build();

app.UseSwaggerConfiguration();


app.UseHttpsRedirection(); // Pour les connexions HTTPS
app.UseAuthorization(); // Autorisation pour les routes sécurisées
app.MapControllers(); // Mappe les contrôleurs aux routes

// Configure the HTTP request pipeline.
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Error");
    // The default HSTS value is 30 days. You may want to change this for production scenarios, see https://aka.ms/aspnetcore-hsts.
    app.UseHsts();
}

app.UseHttpsRedirection();
app.UseStaticFiles();

app.UseRouting();

app.UseAuthorization();

app.MapRazorPages();

app.Run();
