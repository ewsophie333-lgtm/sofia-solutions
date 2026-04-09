type LogoProps = {
  small?: boolean;
  stacked?: boolean;
  className?: string;
};

export default function Logo({ small = false, stacked = false, className = "" }: LogoProps) {
  return (
    <div
      className={`brand-lockup ${stacked ? "stacked" : "inline"} ${small ? "small" : ""} ${className}`.trim()}
    >
      <img
        src="/sofia-logo-login.png?v=20260409"
        alt="Sofia Solutions"
        className="brand-mark"
      />
    </div>
  );
}
